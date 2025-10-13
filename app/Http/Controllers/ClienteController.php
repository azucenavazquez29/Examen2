<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer_1;
use App\Models\Rental_1;
use App\Models\Payment_1;
use Carbon\Carbon;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        // Por ahora simulamos el customer_id
        // En producción esto vendría de Auth::user()->customer_id
        $customerId = $request->get('customer_id', 1); // Cambiar por autenticación real
        
        $customer = Customer_1::with(['address.city.country'])
            ->findOrFail($customerId);
        
        // Historial de rentas (todas, con paginación)
        $rentals = Rental_1::where('customer_id', $customerId)
            ->with(['inventory.film', 'staff'])
            ->orderBy('rental_date', 'desc')
            ->paginate(10, ['*'], 'rentals_page');
        
        // Rentas activas (no devueltas)
        $activeRentals = Rental_1::where('customer_id', $customerId)
            ->whereNull('return_date')
            ->with(['inventory.film'])
            ->orderBy('rental_date', 'desc')
            ->get();
        
        // Pagos realizados
        $payments = Payment_1::where('customer_id', $customerId)
            ->with(['rental.inventory.film', 'staff'])
            ->orderBy('payment_date', 'desc')
            ->paginate(10, ['*'], 'payments_page');
        
        // Calcular estadísticas
        $stats = [
            'total_rentals' => Rental_1::where('customer_id', $customerId)->count(),
            'active_rentals' => $activeRentals->count(),
            'total_paid' => Payment_1::where('customer_id', $customerId)->sum('amount'),
            'overdue_rentals' => $activeRentals->filter(function($rental) {
                return $rental->isOverdue();
            })->count()
        ];
        
        // Notificaciones de alertas
        $notifications = $this->generateNotifications($activeRentals);
        
        return view('cliente.index', compact(
            'customer',
            'rentals',
            'activeRentals',
            'payments',
            'stats',
            'notifications'
        ));
    }
    
    /**
     * Generar notificaciones basadas en rentas activas
     */
    private function generateNotifications($activeRentals)
    {
        $notifications = [];
        
        foreach ($activeRentals as $rental) {
            $daysRented = $rental->rental_date->diffInDays(now());
            $daysAllowed = $rental->inventory->film->rental_duration;
            $daysRemaining = $daysAllowed - $daysRented;
            
            // Notificación de vencimiento próximo (1-2 días antes)
            if ($daysRemaining > 0 && $daysRemaining <= 2) {
                $notifications[] = [
                    'type' => 'warning',
                    'icon' => 'fa-clock',
                    'title' => 'Devolución próxima',
                    'message' => "La película '{$rental->inventory->film->title}' debe devolverse en {$daysRemaining} día(s).",
                    'film' => $rental->inventory->film->title,
                    'date' => $rental->rental_date->addDays($daysAllowed)->format('d/m/Y')
                ];
            }
            
            // Notificación de retraso
            if ($rental->isOverdue()) {
                $daysOverdue = abs($daysRemaining);
                $lateFee = $daysOverdue * 1.00; // $1 por día de retraso
                
                $notifications[] = [
                    'type' => 'danger',
                    'icon' => 'fa-exclamation-triangle',
                    'title' => '¡Renta vencida!',
                    'message' => "La película '{$rental->inventory->film->title}' tiene {$daysOverdue} día(s) de retraso. Cargo adicional: \${$lateFee}",
                    'film' => $rental->inventory->film->title,
                    'days_overdue' => $daysOverdue,
                    'late_fee' => $lateFee
                ];
            }
        }
        
        return $notifications;
    }
}

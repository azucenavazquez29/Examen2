<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function index(Request $request)
    {
        // --- Filtro de sucursal ---
        $storeId = $request->input('store_id');

        // --- KPIs principales (SIN FILTRO DE FECHA) ---
        $totalRentals = $this->getTotalRentals($storeId);
        $totalRevenue = $this->getTotalRevenue($storeId);
        $uniqueFilmsRented = $this->getUniqueFilmsRented($storeId);
        $activeCustomers = $this->getActiveCustomers($storeId);

        // --- Gráficas ---
        $rentalsByStore = $this->getRentalsByStore();
        $revenueByStore = $this->getRevenueByStore();
        $revenueTrend = $this->getRevenueTrend();
        $rentalsByCategory = $this->getRentalsByCategory();

        // --- Rankings ---
        $topFilms = $this->getTopFilms(10);
        $topCustomers = $this->getTopCustomers(10);

        // --- Tablas ---
        $storeStats = $this->getStoreStats();
        $categoryStats = $this->getCategoryStats();

        // --- Filtro de sucursales ---
        $stores = DB::select("
            SELECT 
                s.store_id,
                CONCAT(c.city, ', ', cy.country, ' - Tienda #', s.store_id) AS name
            FROM store s
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country cy ON c.country_id = cy.country_id
            ORDER BY cy.country, c.city
        ");

        return view('stats', compact(
            'totalRentals',
            'totalRevenue',
            'uniqueFilmsRented',
            'activeCustomers',
            'rentalsByStore',
            'revenueByStore',
            'revenueTrend',
            'rentalsByCategory',
            'topFilms',
            'topCustomers',
            'storeStats',
            'categoryStats',
            'stores'
        ));
    }

    // --- KPIs (SIN FILTRO DE FECHA - TODOS LOS DATOS) ---
    private function getTotalRentals($storeId = null)
    {
        $sql = "SELECT COUNT(r.rental_id) as total FROM rental r";
        $params = [];
        
        if ($storeId) {
            $sql .= " INNER JOIN inventory i ON r.inventory_id = i.inventory_id WHERE i.store_id = ?";
            $params[] = $storeId;
        }
        
        $result = DB::select($sql, $params);
        return $result[0]->total ?? 0;
    }

    private function getTotalRevenue($storeId = null)
    {
        $sql = "
            SELECT SUM(p.amount) as total
            FROM payment p
            INNER JOIN rental r ON p.rental_id = r.rental_id
            INNER JOIN inventory i ON r.inventory_id = i.inventory_id
        ";
        
        $params = [];
        
        if ($storeId) {
            $sql .= " WHERE i.store_id = ?";
            $params[] = $storeId;
        }
        
        $result = DB::select($sql, $params);
        return $result[0]->total ?? 0;
    }

    private function getUniqueFilmsRented($storeId = null)
    {
        $sql = "
            SELECT COUNT(DISTINCT i.film_id) as total
            FROM rental r
            INNER JOIN inventory i ON r.inventory_id = i.inventory_id
        ";
        
        $params = [];
        
        if ($storeId) {
            $sql .= " WHERE i.store_id = ?";
            $params[] = $storeId;
        }
        
        $result = DB::select($sql, $params);
        return $result[0]->total ?? 0;
    }

    private function getActiveCustomers($storeId = null)
    {
        $sql = "
            SELECT COUNT(DISTINCT r.customer_id) as total
            FROM rental r
            INNER JOIN inventory i ON r.inventory_id = i.inventory_id
        ";
        
        $params = [];
        
        if ($storeId) {
            $sql .= " WHERE i.store_id = ?";
            $params[] = $storeId;
        }
        
        $result = DB::select($sql, $params);
        return $result[0]->total ?? 0;
    }

    // --- Gráficas (TODOS LOS DATOS) ---
    private function getRentalsByStore()
    {
        $results = DB::select("
            SELECT 
                s.store_id,
                CONCAT(c.city, ', ', cy.country) AS store_name,
                COUNT(r.rental_id) AS total
            FROM store s
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country cy ON c.country_id = cy.country_id
            INNER JOIN inventory i ON s.store_id = i.store_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            GROUP BY s.store_id, c.city, cy.country
            ORDER BY cy.country, c.city
        ");
        
        return collect($results);
    }

    private function getRevenueByStore()
    {
        $results = DB::select("
            SELECT 
                s.store_id,
                CONCAT(c.city, ', ', cy.country) AS store_name,
                SUM(p.amount) AS total
            FROM store s
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country cy ON c.country_id = cy.country_id
            INNER JOIN inventory i ON s.store_id = i.store_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            INNER JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY s.store_id, c.city, cy.country
            ORDER BY cy.country, c.city
        ");
        
        return collect($results);
    }

    private function getRevenueTrend()
    {
        $results = DB::select("
            SELECT 
                DATE_FORMAT(payment_date, '%Y-%m') AS month,
                SUM(amount) AS total
            FROM payment
            GROUP BY month
            ORDER BY month DESC
            LIMIT 12
        ");
        
        return collect($results)->reverse()->values();
    }

    private function getRentalsByCategory()
    {
        $results = DB::select("
            SELECT 
                c.name,
                COUNT(r.rental_id) AS total
            FROM category c
            INNER JOIN film_category fc ON c.category_id = fc.category_id
            INNER JOIN film f ON fc.film_id = f.film_id
            INNER JOIN inventory i ON f.film_id = i.film_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            GROUP BY c.category_id, c.name
            ORDER BY total DESC
        ");
        
        return collect($results);
    }

    // --- Rankings ---
    private function getTopFilms($limit = 10)
    {
        $results = DB::select("
            SELECT 
                f.title,
                COUNT(r.rental_id) AS rental_count,
                SUM(p.amount) AS total_income
            FROM film f
            INNER JOIN inventory i ON f.film_id = i.film_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            INNER JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY f.film_id, f.title
            ORDER BY rental_count DESC
            LIMIT ?
        ", [$limit]);
        
        return collect($results);
    }

    private function getTopCustomers($limit = 10)
    {
        $results = DB::select("
            SELECT 
                CONCAT(c.first_name, ' ', c.last_name) AS name,
                COUNT(r.rental_id) AS rental_count,
                SUM(p.amount) AS total_spent
            FROM customer c
            INNER JOIN rental r ON c.customer_id = r.customer_id
            INNER JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY c.customer_id, c.first_name, c.last_name
            ORDER BY total_spent DESC
            LIMIT ?
        ", [$limit]);
        
        return collect($results);
    }

    // --- Tablas ---
    private function getStoreStats()
    {
        $results = DB::select("
            SELECT 
                s.store_id,
                CONCAT(c.city, ', ', cy.country) AS store_name,
                CONCAT(st.first_name, ' ', st.last_name) AS manager,
                COUNT(DISTINCT r.rental_id) AS total_rentals,
                COALESCE(SUM(p.amount), 0) AS total_revenue,
                COALESCE(AVG(p.amount), 0) AS avg_ticket
            FROM store s
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country cy ON c.country_id = cy.country_id
            INNER JOIN staff st ON s.manager_staff_id = st.staff_id
            LEFT JOIN inventory i ON s.store_id = i.store_id
            LEFT JOIN rental r ON i.inventory_id = r.inventory_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY s.store_id, c.city, cy.country, st.first_name, st.last_name
            ORDER BY total_revenue DESC
        ");
        
        return collect($results);
    }

    private function getCategoryStats()
    {
        $results = DB::select("
            SELECT 
                c.name,
                COUNT(DISTINCT r.rental_id) AS total_rentals,
                COALESCE(SUM(p.amount), 0) AS total_revenue,
                COALESCE(AVG(p.amount), 0) AS avg_ticket,
                COUNT(DISTINCT f.film_id) AS film_count,
                COUNT(i.inventory_id) AS inventory_count
            FROM category c
            INNER JOIN film_category fc ON c.category_id = fc.category_id
            INNER JOIN film f ON fc.film_id = f.film_id
            INNER JOIN inventory i ON f.film_id = i.film_id
            LEFT JOIN rental r ON i.inventory_id = r.inventory_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY c.category_id, c.name
            ORDER BY total_rentals DESC
        ");
        
        return collect($results);
    }
}
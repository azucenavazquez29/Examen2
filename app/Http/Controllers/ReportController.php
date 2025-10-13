<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    // --------------------
    // INDEX - Panel de Reportes
    // --------------------
    public function index()
    {
        return view('reportes.index');
    }

    // --------------------
    // SALES BY STORE (Rentas por Sucursal)
    // --------------------
    public function salesByStore()
    {
        $data = DB::select("
            SELECT
                CONCAT(c.city, ', ', cy.country) AS store,
                CONCAT(m.first_name, ' ', m.last_name) AS manager,
                SUM(p.amount) AS total_sales
            FROM payment p
            INNER JOIN rental r ON p.rental_id = r.rental_id
            INNER JOIN inventory i ON r.inventory_id = i.inventory_id
            INNER JOIN store s ON i.store_id = s.store_id
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country cy ON c.country_id = cy.country_id
            INNER JOIN staff m ON s.manager_staff_id = m.staff_id
            GROUP BY s.store_id, c.city, cy.country, m.first_name, m.last_name
            ORDER BY cy.country, c.city
        ");
        return view('reportes.sales_by_store', compact('data'));
    }

    public function exportSalesByStoreCsv()
    {
        $data = DB::select("
            SELECT
                CONCAT(c.city, ', ', cy.country) AS store,
                CONCAT(m.first_name, ' ', m.last_name) AS manager,
                SUM(p.amount) AS total_sales
            FROM payment p
            INNER JOIN rental r ON p.rental_id = r.rental_id
            INNER JOIN inventory i ON r.inventory_id = i.inventory_id
            INNER JOIN store s ON i.store_id = s.store_id
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country cy ON c.country_id = cy.country_id
            INNER JOIN staff m ON s.manager_staff_id = m.staff_id
            GROUP BY s.store_id, c.city, cy.country, m.first_name, m.last_name
            ORDER BY cy.country, c.city
        ");
        
        $filename = "rentas_por_sucursal_" . date('Y-m-d') . ".csv";
        $handle = fopen($filename, 'w+');
        
        // Encabezados
        fputcsv($handle, ['Sucursal', 'Gerente', 'Total Ventas']);
        
        // Datos
        foreach ($data as $row) {
            fputcsv($handle, [$row->store, $row->manager, '$' . number_format($row->total_sales, 2)]);
        }
        
        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

public function exportSalesByStorePdf()
{
    $data = DB::select("
        SELECT
            CONCAT(c.city, ', ', cy.country) AS store,
            CONCAT(m.first_name, ' ', m.last_name) AS manager,
            SUM(p.amount) AS total_sales
        FROM payment p
        INNER JOIN rental r ON p.rental_id = r.rental_id
        INNER JOIN inventory i ON r.inventory_id = i.inventory_id
        INNER JOIN store s ON i.store_id = s.store_id
        INNER JOIN address a ON s.address_id = a.address_id
        INNER JOIN city c ON a.city_id = c.city_id
        INNER JOIN country cy ON c.country_id = cy.country_id
        INNER JOIN staff m ON s.manager_staff_id = m.staff_id
        GROUP BY s.store_id, c.city, cy.country, m.first_name, m.last_name
        ORDER BY cy.country, c.city
    ");
    
    $total = array_sum(array_column($data, 'total_sales'));

    // Genera el HTML como antes
    $html = view('reportes.pdf.sales_by_store', compact('data', 'total'))->render();

    // 🔹 Usa DomPDF
    $pdf = \PDF::loadHTML($html)->setPaper('a4', 'portrait');
    
    // Descarga el PDF
    return $pdf->download('rentas_por_sucursal_' . date('Y-m-d') . '.pdf');
}


    // --------------------
    // SALES BY CATEGORY (Rentas por Categoría)
    // --------------------
    public function salesByCategory()
    {
        $data = DB::table('sales_by_film_category')
            ->orderBy('total_sales', 'DESC')
            ->get();
        return view('reportes.sales_by_category', compact('data'));
    }

    public function exportSalesByCategoryCsv()
    {
        $data = DB::table('sales_by_film_category')
            ->orderBy('total_sales', 'DESC')
            ->get();
        
        $filename = "rentas_por_categoria_" . date('Y-m-d') . ".csv";
        $handle = fopen($filename, 'w+');
        
        fputcsv($handle, ['Categoría', 'Total Ventas']);
        
        foreach ($data as $row) {
            fputcsv($handle, [$row->category, '$' . number_format($row->total_sales, 2)]);
        }
        
        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

 public function exportSalesByCategoryPdf()
    {
        $data = DB::select("
            SELECT
                c.name AS category,
                COUNT(r.rental_id) AS total_rentals,
                SUM(p.amount) AS total_income
            FROM category c
            INNER JOIN film_category fc ON c.category_id = fc.category_id
            INNER JOIN film f ON fc.film_id = f.film_id
            INNER JOIN inventory i ON f.film_id = i.film_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            INNER JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY c.category_id, c.name
            ORDER BY total_income DESC
        ");

        $total = array_sum(array_column($data, 'total_income'));
        $html = view('reportes.pdf.sales_by_category', compact('data', 'total'))->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('rentas_por_categoria_' . date('Y-m-d') . '.pdf');
    }


    // --------------------
    // SALES BY ACTOR (Rentas por Actor)
    // --------------------
    public function salesByActor()
    {
        $data = DB::select("
            SELECT 
                CONCAT(a.first_name, ' ', a.last_name) as actor,
                COUNT(r.rental_id) as total_rentas,
                SUM(p.amount) as ingresos_totales
            FROM actor a
            INNER JOIN film_actor fa ON a.actor_id = fa.actor_id
            INNER JOIN film f ON fa.film_id = f.film_id
            INNER JOIN inventory i ON f.film_id = i.film_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY a.actor_id, a.first_name, a.last_name
            ORDER BY total_rentas DESC
            LIMIT 50
        ");
        return view('reportes.sales_by_actor', compact('data'));
    }

    public function exportSalesByActorCsv()
    {
        $data = DB::select("
            SELECT 
                CONCAT(a.first_name, ' ', a.last_name) as actor,
                COUNT(r.rental_id) as total_rentas,
                SUM(p.amount) as ingresos_totales
            FROM actor a
            INNER JOIN film_actor fa ON a.actor_id = fa.actor_id
            INNER JOIN film f ON fa.film_id = f.film_id
            INNER JOIN inventory i ON f.film_id = i.film_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY a.actor_id, a.first_name, a.last_name
            ORDER BY total_rentas DESC
            LIMIT 50
        ");
        
        $filename = "rentas_por_actor_" . date('Y-m-d') . ".csv";
        $handle = fopen($filename, 'w+');
        
        fputcsv($handle, ['Actor', 'Total Rentas', 'Ingresos Totales']);
        
        foreach ($data as $row) {
            fputcsv($handle, [
                $row->actor, 
                $row->total_rentas, 
                '$' . number_format($row->ingresos_totales ?? 0, 2)
            ]);
        }
        
        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

   public function exportSalesByActorPdf()
    {
        $data = DB::select("
            SELECT
                CONCAT(a.first_name, ' ', a.last_name) AS actor,
                COUNT(r.rental_id) AS total_rentals,
                SUM(p.amount) AS total_income
            FROM actor a
            INNER JOIN film_actor fa ON a.actor_id = fa.actor_id
            INNER JOIN film f ON fa.film_id = f.film_id
            INNER JOIN inventory i ON f.film_id = i.film_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            INNER JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY a.actor_id, a.first_name, a.last_name
            ORDER BY total_income DESC
        ");

        $total = array_sum(array_column($data, 'total_income'));
        $html = view('reportes.pdf.sales_by_actor', compact('data', 'total'))->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('rentas_por_actor_' . date('Y-m-d') . '.pdf');
    }

    // --------------------
    // INCOME BY STORE (Ingresos por Tienda)
    // --------------------
    public function incomeByStore()
    {
        $data = DB::select("
            SELECT 
                s.store_id,
                CONCAT(c.city, ', ', co.country) as tienda,
                CONCAT(st.first_name, ' ', st.last_name) as gerente,
                COUNT(DISTINCT r.rental_id) as total_rentas,
                SUM(p.amount) as ingresos_totales,
                AVG(p.amount) as promedio_por_renta
            FROM store s
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country co ON c.country_id = co.country_id
            INNER JOIN staff st ON s.manager_staff_id = st.staff_id
            LEFT JOIN inventory i ON s.store_id = i.store_id
            LEFT JOIN rental r ON i.inventory_id = r.inventory_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY s.store_id, c.city, co.country, st.first_name, st.last_name
            ORDER BY ingresos_totales DESC
        ");
        return view('reportes.income_by_store', compact('data'));
    }

    public function exportIncomeByStoreCsv()
    {
        $data = DB::select("
            SELECT 
                s.store_id,
                CONCAT(c.city, ', ', co.country) as tienda,
                CONCAT(st.first_name, ' ', st.last_name) as gerente,
                COUNT(DISTINCT r.rental_id) as total_rentas,
                SUM(p.amount) as ingresos_totales,
                AVG(p.amount) as promedio_por_renta
            FROM store s
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country co ON c.country_id = co.country_id
            INNER JOIN staff st ON s.manager_staff_id = st.staff_id
            LEFT JOIN inventory i ON s.store_id = i.store_id
            LEFT JOIN rental r ON i.inventory_id = r.inventory_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY s.store_id, c.city, co.country, st.first_name, st.last_name
            ORDER BY ingresos_totales DESC
        ");
        
        $filename = "ingresos_por_tienda_" . date('Y-m-d') . ".csv";
        $handle = fopen($filename, 'w+');
        
        fputcsv($handle, ['ID', 'Tienda', 'Gerente', 'Total Rentas', 'Ingresos Totales', 'Promedio']);
        
        foreach ($data as $row) {
            fputcsv($handle, [
                $row->store_id,
                $row->tienda,
                $row->gerente,
                $row->total_rentas,
                '$' . number_format($row->ingresos_totales ?? 0, 2),
                '$' . number_format($row->promedio_por_renta ?? 0, 2)
            ]);
        }
        
        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

        public function exportIncomeByStorePdf()
    {
        $data = DB::select("
            SELECT
                s.store_id,
                CONCAT(c.city, ', ', cy.country) AS location,
                SUM(p.amount) AS total_income
            FROM payment p
            INNER JOIN rental r ON p.rental_id = r.rental_id
            INNER JOIN inventory i ON r.inventory_id = i.inventory_id
            INNER JOIN store s ON i.store_id = s.store_id
            INNER JOIN address a ON s.address_id = a.address_id
            INNER JOIN city c ON a.city_id = c.city_id
            INNER JOIN country cy ON c.country_id = cy.country_id
            GROUP BY s.store_id, c.city, cy.country
            ORDER BY total_income DESC
        ");

        $total = array_sum(array_column($data, 'total_income'));
        $html = view('reportes.pdf.income_by_store', compact('data', 'total'))->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('ingresos_por_sucursal_' . date('Y-m-d') . '.pdf');
    }

    // --------------------
    // TOP MOVIES (Películas Más Rentadas)
    // --------------------
    public function topMovies()
    {
        $data = DB::select("
            SELECT 
                f.film_id,
                f.title as titulo,
                c.name as categoria,
                COUNT(r.rental_id) as veces_rentada,
                SUM(p.amount) as ingresos_generados
            FROM film f
            LEFT JOIN film_category fc ON f.film_id = fc.film_id
            LEFT JOIN category c ON fc.category_id = c.category_id
            LEFT JOIN inventory i ON f.film_id = i.film_id
            LEFT JOIN rental r ON i.inventory_id = r.inventory_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY f.film_id, f.title, c.name
            ORDER BY veces_rentada DESC
            LIMIT 100
        ");
        return view('reportes.top_movies', compact('data'));
    }

    public function exportTopMoviesCsv()
    {
        $data = DB::select("
            SELECT 
                f.film_id,
                f.title as titulo,
                c.name as categoria,
                COUNT(r.rental_id) as veces_rentada,
                SUM(p.amount) as ingresos_generados
            FROM film f
            LEFT JOIN film_category fc ON f.film_id = fc.film_id
            LEFT JOIN category c ON fc.category_id = c.category_id
            LEFT JOIN inventory i ON f.film_id = i.film_id
            LEFT JOIN rental r ON i.inventory_id = r.inventory_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY f.film_id, f.title, c.name
            ORDER BY veces_rentada DESC
            LIMIT 100
        ");
        
        $filename = "peliculas_mas_rentadas_" . date('Y-m-d') . ".csv";
        $handle = fopen($filename, 'w+');
        
        fputcsv($handle, ['Ranking', 'Título', 'Categoría', 'Veces Rentada', 'Ingresos']);
        
        $ranking = 1;
        foreach ($data as $row) {
            fputcsv($handle, [
                $ranking++,
                $row->titulo,
                $row->categoria ?? 'N/A',
                $row->veces_rentada,
                '$' . number_format($row->ingresos_generados ?? 0, 2)
            ]);
        }
        
        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

   public function exportTopMoviesPdf()
    {
        $data = DB::select("
            SELECT
                f.title AS movie,
                COUNT(r.rental_id) AS total_rentals,
                SUM(p.amount) AS total_income
            FROM film f
            INNER JOIN inventory i ON f.film_id = i.film_id
            INNER JOIN rental r ON i.inventory_id = r.inventory_id
            INNER JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY f.film_id, f.title
            ORDER BY total_rentals DESC
            LIMIT 10
        ");

        $html = view('reportes.pdf.top_movies', compact('data'))->render();
        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('top_peliculas_' . date('Y-m-d') . '.pdf');
    }
    // --------------------
    // TOP CUSTOMERS (Clientes con Más Rentas)
    // --------------------
    public function topCustomers()
    {
        $data = DB::select("
            SELECT 
                c.customer_id,
                CONCAT(c.first_name, ' ', c.last_name) as cliente,
                c.email,
                ci.city as ciudad,
                COUNT(r.rental_id) as total_rentas,
                SUM(p.amount) as total_pagado
            FROM customer c
            LEFT JOIN address a ON c.address_id = a.address_id
            LEFT JOIN city ci ON a.city_id = ci.city_id
            LEFT JOIN rental r ON c.customer_id = r.customer_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY c.customer_id, c.first_name, c.last_name, c.email, ci.city
            ORDER BY total_rentas DESC
            LIMIT 100
        ");
        return view('reportes.top_customers', compact('data'));
    }

    public function exportTopCustomersCsv()
    {
        $data = DB::select("
            SELECT 
                c.customer_id,
                CONCAT(c.first_name, ' ', c.last_name) as cliente,
                c.email,
                ci.city as ciudad,
                COUNT(r.rental_id) as total_rentas,
                SUM(p.amount) as total_pagado
            FROM customer c
            LEFT JOIN address a ON c.address_id = a.address_id
            LEFT JOIN city ci ON a.city_id = ci.city_id
            LEFT JOIN rental r ON c.customer_id = r.customer_id
            LEFT JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY c.customer_id, c.first_name, c.last_name, c.email, ci.city
            ORDER BY total_rentas DESC
            LIMIT 100
        ");
        
        $filename = "clientes_frecuentes_" . date('Y-m-d') . ".csv";
        $handle = fopen($filename, 'w+');
        
        fputcsv($handle, ['Ranking', 'Cliente', 'Email', 'Ciudad', 'Total Rentas', 'Total Pagado']);
        
        $ranking = 1;
        foreach ($data as $row) {
            fputcsv($handle, [
                $ranking++,
                $row->cliente,
                $row->email,
                $row->ciudad,
                $row->total_rentas,
                '$' . number_format($row->total_pagado ?? 0, 2)
            ]);
        }
        
        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

     public function exportTopCustomersPdf()
    {
        $data = DB::select("
            SELECT
                CONCAT(c.first_name, ' ', c.last_name) AS customer,
                COUNT(r.rental_id) AS total_rentals,
                SUM(p.amount) AS total_spent
            FROM customer c
            INNER JOIN rental r ON c.customer_id = r.customer_id
            INNER JOIN payment p ON r.rental_id = p.rental_id
            GROUP BY c.customer_id, c.first_name, c.last_name
            ORDER BY total_spent DESC
            LIMIT 10
        ");

        $total = array_sum(array_column($data, 'total_spent'));
        $html = view('reportes.pdf.top_customers', compact('data', 'total'))->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('top_clientes_' . date('Y-m-d') . '.pdf');
    }
}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; color: #2a2a2a; margin-bottom: 5px; }
        p { text-align: center; margin-top: 0; font-size: 10px; color: #777; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #4CAF50; color: #fff; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .total { font-weight: bold; background-color: #e0e0e0; }
    </style>
</head>
<body>
    <h2>📊 {{ $title }}</h2>
    <p>Generado el {{ date('d/m/Y H:i') }}</p>

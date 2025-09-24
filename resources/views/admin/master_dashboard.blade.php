@extends('layouts.admin')

@section('title', 'Guest')

@section('content')
    <h1 class="h2 main-text">Guest</h1>

    <div class="card">
        <div class="card-header">
            <h5>Guest List</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Name</th>
                        <th>Room Number</th>
                        <th>Total Amount</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7">No guests found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layouts.admin')
@section('title', 'Deals')
@section('content')
    <h1 class="main-text">Deals</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <h4>Available Deals</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Reference Number</th>
                            <th>Deal Name</th>
                            <th>Reservations Left</th>
                            <th>End Date</th>
                            <th>Room Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6">No deals available.</td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addDealModal">Add Deal</button>
            </div>
        </div>
    </div>
@endsection

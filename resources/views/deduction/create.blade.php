@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')

{{-- @endif --}}

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Deductation</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('dashboard') }}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customer.index') }}">Deductation</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Add Deductation</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Deductation</div>
                        </div>
                        <form method="POST" action="{{ route('admin.customer.store') }}" id="customerForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Customer<span style="color: red">*</span></label>
                                            <select name="customer_id" class="form-control" id="">
                                                <option value="">Select Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="basic-datatables" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Invoice</th>
                                                <th>Customer</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="financeDetails">
                                            <tr>
                                                <td colspan="6" class="text-center">No data available</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.customer.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="paymentForm" method="POST" action="">
                @csrf
                <input type="hidden" name="finance_id" id="modalFinanceId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Invoice</label>
                            <input type="text" class="form-control" id="modalInvoice" readonly>
                        </div>
                        <div class="form-group">
                            <label>Customer</label>
                            <input type="text" class="form-control" id="modalCustomer" readonly>
                        </div>
                        <div class="form-group">
                            <label>EMI Value</label>
                            <span id="modalEmiValue"></span>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Method</label>
                            <select name="method" class="form-control" required>
                                <option value="">Select</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Online">Online</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Paid At</label>
                            <input type="date" name="paid_at" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit Payment</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('footer-script')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
    <script>
        $(document).on('click', '.btn-pay', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const invoice = $(this).data('invoice');
            const customer = $(this).data('customer');
            const Emi_value = $(this).data('emivalue');
            // console.log(Emi_value)

            $('#modalFinanceId').val(id);
            $('#modalInvoice').val(invoice);
            $('#modalCustomer').val(customer);
            $('#modalEmiValue').val(Emi_value);

            $('#paymentModal').modal('show');
        });
        $('select[name="customer_id"]').on('change', function() {
            const customerId = $(this).val();
            if (!customerId) {
                $('#financeDetails').html('');
                return;
            }

            $.ajax({
                url: '{{ route('admin.customer.finance.details') }}',
                method: 'POST',
                data: {
                    customer_id: customerId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    const finance = response.finance;

                    if (finance.length === 0) {
                        $('#financeDetails').html(
                            '<tr><td colspan="4" class="text-center">No finance data found</td></tr>'
                        );
                        return;
                    }
                    console.log(finance);
                    let html = '';
                    finance.forEach((item, index) => {
                        html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.invoice_no ?? 'N/A'}</td>
                            <td>${item.customer_name ?? 'N/A'}</td>
                            <td>
                                 <button class="btn btn-sm btn-success btn-pay" data-id="${item.id}" data-invoice="${item.invoice_no ?? ''}" data-emiValue="${item?.emi_value ?? ''}" data-customer="${item?.customer_name ?? ''}">Pay</button>
                            </td>
                        </tr>`;
                    });

                    $('#financeDetails').html(html);


                },
                error: function(xhr) {
                    $('#financeDetails').html(
                        '<div class="alert alert-danger">Unable to fetch finance details.</div>');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $('input[name="phone"]').mask('0000000000');

            $('#name, #city').inputmask({
                regex: "^[a-zA-Z ]*$",
                placeholder: ''
            });

            $('#customerForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    phone: {
                        required: true
                    },

                    address: {
                        required: true
                    },
                    city: {
                        required: true
                    }

                },
                messages: {
                    name: {
                        required: "Please enter a customer name",
                        minlength: "customer name must be at least 2 characters long"
                    },
                    phone: {
                        required: "Please enter phone number"
                    },
                    address: {
                        required: "Please enter address"
                    },
                    city: {
                        required: "please enter city"
                    }
                },
                errorElement: 'span',
                errorClass: 'text-danger',
                highlight: function(element, errorClass) {
                    $(element).addClass("is-invalid");


                },
                unhighlight: function(element, errorClass) {
                    $(element).removeClass("is-invalid");
                }
            });

        });
    </script>
@endsection

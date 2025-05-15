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
                        <a href="{{ route('admin.deduction.index') }}">Deductation</a>
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
                                        <th>Sr No</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>City</th>
                                        <th>File Number</th>
                                        <th>Deduction Date</th>
                                        <th>Brand Name</th>
                                        <th>Product Name</th>
                                        <th>Total Emi</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="financeDetails">
                                    <tr>
                                        <td colspan="11" class="text-center">No data available</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="paymentForm" method="POST" action="{{ route('admin.deduction.store') }}">
                @csrf
                <input type="hidden" name="finance_id" id="modalFinanceId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="form-group">
                                <label>Invoice</label>
                                <input type="text" class="form-control" id="modalInvoice" readonly>
                            </div>
                            <div class="form-group">
                                <label>Customer</label>
                                <input type="text" class="form-control" id="modalCustomer" readonly>
                                <input type="hidden" class="form-control" name="customer_id" id="modalCustomerId" readonly>
                            </div>
                            <div class="form-group">
                                <label>Down Payment</label>
                                <input type="text" class="form-control" id="modalDownPayment" readonly>
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" name="emi_value_paid" id="modalAmount" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Method</label>
                                <select name="payment_mode" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="1">Online</option>
                                    <option value="2">Cash</option>
                                </select>
                            </div>
                            <div class="form-group d-none">
                                <label>Referene no </label>
                                <input type="text" name="refernce_no" class="form-control" required>
                            </div>
                        </div>
                        <input type="hidden" name="emi_value" id="EmiValue" value="0">
                        <input type="hidden" name="penalty" id="modalPenalty" value="0">
                        <input type="hidden" name="remaining" id="modalRemaining" value="0">
                        <input type="hidden" name="total" id="modalTotal" value="0">
                        <input type="hidden" name="oldremaining" id="modaloldremaining" value="0">


                        <div class="form-group  d-flex justify-content-between">
                            <div>
                                <p>EMI Value: <span id="modalEmiValue"></span></p>
                                <p>Month duration: <span id="modalMonthDuration"></span></p>
                            </div>
                            <div>
                                <p>Paid EMI : <span id="modalPaidEMI"></span></p>
                                <p>Paid EMI Value : <span id="modalPaidemiValue"></span></p>
                            </div>

                        </div>
                        <div class="form-group  d-flex justify-content-between">
                            <p>Penalty Amount: <span id="modalPenaltyAmount"></span></p>
                            <p>Remaining Amount: <span id="modalRemainingAmount"></span></p>
                        </div>
                        <div class="form-group  d-flex justify-content-between">
                            <p>Old Remaing Amount: <span id="modalOldRemainingAmount"></span></p>
                            <p>Total Amount: <span id="modalTotalAmount"></span></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit Payment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
        function updateCalculations() {
            const emiValue = parseFloat($('#EmiValue').val()) || 0;
            const paidValue = parseFloat($('#modalAmount').val()) || 0;
            const penalty = parseFloat($('#modalPenalty').val()) || 0;
            const oldremaining = parseFloat($('#modaloldremaining').val()) || 0;

            const remaining = emiValue - paidValue;
            const total = paidValue + penalty + oldremaining;

            $('#modalRemaining').val(remaining.toFixed(2));
            $('#modalTotal').val(total.toFixed(2));
            $('#modalRemainingAmount').text(remaining.toFixed(2));
            $('#modalTotalAmount').text(total.toFixed(2));
        }

        $(document).on('click', '.btn-pay', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const invoice = $(this).data('invoice');
            const customer = $(this).data('customer');
            const customer_id = $(this).data('customer-id');
            const downpayment = $(this).data('down-payment');
            const Emi_value = $(this).data('emivalue');
            const month_duration = $(this).data('month-duration');
            const penalty = $(this).data('penalty');
            const deduction = $(this).data('deduction-date');
            const todayDay = new Date().getDate();

            if (todayDay > deduction) {
                $('#modalPenalty').val(penalty);
                $('#modalPenaltyAmount').html(penalty);
                $('#modalPenaltyAmount').closest('.form-group').show();
            } else {
                $('#modalPenalty').val(0);
                $('#modalPenaltyAmount').html('0.00');
                $('#modalPenaltyAmount').closest('.form-group').hide();
            }
            ;

            // console.log(Emi_value)

            $('#modalFinanceId').val(id);
            $('#modalInvoice').val(invoice);
            $('#modalDownPayment').val(downpayment);
            $('#modalCustomer').val(customer);
            $('#modalCustomerId').val(customer_id);
            $('#modalEmiValue').html(Emi_value);
            $('#modalAmount').val(Emi_value);
            $('#EmiValue').val(Emi_value);
            $('#modalMonthDuration').html(month_duration);


            $.ajax({
                url: '{{ route('admin.finance.deductions') }}',
                method: 'POST',
                data: {
                    finance_id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    const deductions = response.deductions;
                    const totalemivalue = response.totalemivalue;
                    const remaining = response.remaining;
                    console.log(remaining)
                    $('#modalPaidEMI').html(deductions);
                    $('#modalPaidemiValue').html(totalemivalue);
                    $('#modalOldRemainingAmount').html(remaining);
                    $('#modaloldremaining').val(remaining);
                    updateCalculations();

                },
                error: function () {
                    $('#deductionDetails').html(
                        '<div class="alert alert-danger">Failed to load deductions.</div>');
                }
            });

            $('#paymentModal').modal('show');
            updateCalculations();
        });

        $('#modalAmount').on('input', updateCalculations);

        $('select[name="customer_id"]').on('change', function () {
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
                success: function (response) {
                    const finance = response.finance;

                    if (finance.length === 0) {
                        $('#financeDetails').html(
                            '<tr><td colspan="5" class="text-center">No finance data found</td></tr>'
                        );
                        return;
                    }

                    let html = '';
                    finance.forEach((item, index) => {
                        let url = `show/${item.customer_id}/${item.id}`;
                        html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.customer_name ?? 'N/A'}</td>
                            <td>${item.phone ?? 'N/A'}</td>
                            <td>${item.city ?? 'N/A'}</td>
                            <td>${item.file_no ?? '-'}</td>
                            <td>${item.dedication_date ?? 'N/A'}</td>
                            <td>${item.brand_name ?? 'N/A'}</td>
                            <td>${item.product_name ?? 'N/A'}</td>
                             <td>${item.month_duration ?? 'N/A'}</td>
                          <td>
                            ${item.status === 'pending'
                            ? '<span class="badge bg-success">Running</span>'
                            : '<span class="badge bg-danger">NIl</span>'
                            }
                         </td>
                   
                        <td>
                                 ${item.status === 'paid'
                            ? `<a href="${url}" class="btn btn-sm btn-success" disabled style="pointer-events: none; opacity: 0.6;">
                                View EMI Details
                                </a>`
                            : `<a href="${url}" class="btn btn-sm btn-success">
                           View EMI Details
                                    </a>`
                                 }
                           </td>

                    </tr>`;
                    });

                    $('#financeDetails').html(html);


                },
                error: function (xhr) {
                    $('#financeDetails').html(
                        '<div class="alert alert-danger">Unable to fetch finance details.</div>');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            const referenceGroup = $('input[name="refernce_no"]').closest('.form-group');

            // Toggle reference no field visibility
            $('select[name="payment_mode"]').on('change', function () {
                if ($(this).val() === '1') {
                    referenceGroup.removeClass('d-none');
                } else {
                    referenceGroup.addClass('d-none');
                }
            });

            // Form validation
            $('#paymentForm').validate({
                rules: {
                    emi_value_paid: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    payment_mode: {
                        required: true
                    },
                    refernce_no: {
                        required: function (element) {
                            return $('select[name="payment_mode"]').val() === '1';
                        }
                    }
                },
                messages: {
                    emi_value_paid: {
                        required: "Please enter the amount.",
                        number: "Please enter a valid number.",
                        min: "Amount must be greater than 1."
                    },
                    payment_mode: {
                        required: "Please select a payment method."
                    },
                    refernce_no: {
                        required: "Reference number is required for online payments."
                    }
                },
                errorElement: 'span',
                errorClass: 'text-danger',
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection

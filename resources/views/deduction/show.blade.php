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
                        <a href="#">Show EMI details</a>
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
                            <div class="table-responsive">
                                <table class="table" id="purchaseTable">
                                    <thead>
                                    <tr>
                                        <th>Deduction Date</th>
                                        <th>Status</th>
                                        <th>Remaining Amount</th>
                                        <th>EMI Amount</th>
                                        <th>Payable Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Total</th>
                                        <th>Paid Date</th>
                                        <th>Payment Mode</th>
                                        <th>Action</th>
                                        <th>Transaction Id</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $previousRemaining = 0;
                                    @endphp

                                    @foreach($deductions as $row)
                                        <tr class="deduction-row">
                                            <td>{{ \Carbon\Carbon::parse($row->emi_date)->format('d.m.y') }}</td>
                                            <td>
                                                @if($row['status'] === 'paid')
                                                    <span class="badge bg-success">{{ $row['status'] }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $row['status'] }}</span>
                                                @endif
                                            </td>
                                            <td class="remaining-amount">{{ $row->remaining !== null ? number_format($row->remaining, 2) : '-' }}</td>
                                            <td>{{ $row->emi_value !== null ? number_format($row->emi_value, 2) : '-' }}</td>

                                            @if($row->status == 'paid')
                                                <td id="">‑</td>
                                            @else
                                            <td>
                                                @php
                                                    $emi = $row->emi_value ?? 0;
                                                    $payable = $emi + $previousRemaining;
                                                @endphp
                                                {{ number_format($payable, 2) }}
                                            </td>
                                            @endif
                                        @php
                                            $previousRemaining = $row->remaining ?? 0;
                                        @endphp

                                            <td>{{$row->emi_value_paid ?? '-'}}</td>
                                            <td>{{$row->total ?? '-'}}</td>
                                            <td>{{ $row->created_at == $row->updated_at ? '-' : \Carbon\Carbon::parse( $row->updated_at )->format('d.m.y') }}
                                            </td>
                                            <td>{{ config('constants.database_enum.deductions.payment_mode.name')[ (int) $row->payment_mode] ?? '-' }}</td>
                                            <td>
                                                @if($row['status'] === 'paid')
                                                    <button
                                                            class="btn btn-sm btn-success btn-pay" disabled
                                                    >Paid
                                                    </button>
                                                @else
                                                    <button
                                                            class="btn btn-sm btn-success btn-pay"
                                                            data-id="{{ $row->id ?? '' }}"
                                                            data-invoice="{{ $row->invoice_no }}"
                                                            data-finance-id="{{ $row->finance_id }}"
                                                            data-emiValue="{{ $row->emi_value}}"
                                                            data-dediction-date="{{ $row->dedication_date }}"
                                                            data-penalty="{{ $row->penalty ?? '' }}"
                                                            data-down-payment="{{ $row->downpayment ?? '' }}"
                                                            data-customer="{{ $row->customer_name ?? '' }}"
                                                            data-customer-id="{{ $row->customer_id ?? '' }}"
                                                            data-month-duration="{{ $row->month_duration ?? '' }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#paymentModal"
                                                    >Pay
                                                    </button>
                                                @endif
                                            </td>

                                            <td>{{$row->refernce_no ?? '-'}}</td>
                                        </tr>
                                    @endforeach
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
            <form id="paymentForm" method="POST" action="{{ route('deduction.pay') }}">
                @csrf
                <input type="hidden" name="id" id="modalDeductionId">
                <input type="hidden" name="finance_id" id="modalFinanceId">
{{--                <input type="hidden" name="customer_id" id="modalCustomerId">--}}

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
                        <input type="hidden" name="remaining" id="modalRemaining" value="0" class="remaining-amount">
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
                            <p>Old Remaining Amount: <span id="modalOldRemainingAmount"></span></p>
                            <p>Total Amount: <span id="modalTotalAmount" class="modalTotalAmount"></span></p>
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
        // function updateCalculations() {
        //     console.log('Calculating Calculations');
        //     const emiValue = parseFloat($('#EmiValue').val()) || 0;
        //     const paidValue = parseFloat($('#modalAmount').val()) || 0;
        //     const penalty = parseFloat($('#modalPenalty').val()) || 0;
        //     const oldremaining = parseFloat($('#modaloldremaining').val()) || 0;
        //
        //     const totalDue = emiValue + oldremaining;
        //     const remaining = totalDue - paidValue;
        //     const total = totalDue + penalty;
        //
        //
        //     $('#modalRemaining').val(remaining.toFixed(2));
        //     $('#modalTotal').val(total.toFixed(2));
        //     $('#modalRemainingAmount').text(remaining.toFixed(2));
        //     $('#modalTotalAmount').text(total.toFixed(2));
        // }

        function updateCalculations() {
            const emiValue     = +$('#EmiValue').val()          || 0;   // current EMI
            const paidValue    = +$('#modalAmount').val()       || 0;   // what user will actually pay now
            const penalty      = +$('#modalPenalty').val()      || 0;   // late‑fee
            const oldRemaining = +$('#modaloldremaining').val() || 0;   // previous balance

            const remaining = (emiValue + oldRemaining) - paidValue;

            const total = paidValue + penalty;
            $('#modalRemaining').val(remaining.toFixed(2));
            $('#modalTotal').val(total.toFixed(2));
            $('#modalRemainingAmount').text(remaining.toFixed(2));
            $('#modalTotalAmount').text(total.toFixed(2));
        }

        function checkAndAppendPaymentRow() {
            const $rows = $('.deduction-row');
            const $lastRow = $rows.last();
            const lastRemainingText = $lastRow.find('.remaining-amount').text().replace(/,/g, '');
            const lastRemaining = parseFloat(lastRemainingText);
            // const lastRow1 = $('#purchaseTable tbody tr:last');
            if (!isNaN(lastRemaining) && lastRemaining > 0) {
                // Call backend or clone row template — this is a simplified version
                const newRow = `

            <tr class="deduction-row new-payment-row">
                   <td>-</td>

                <td><span class="badge bg-danger">pending</span></td>

                <td>-</td>

                  <td class="remaining-amount">${lastRemaining.toFixed(2)}</td>
                   <td class="remaining-amount">${lastRemaining.toFixed(2)}</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td> <button
    class="btn btn-sm btn-success btn-pay"
                                                            data-invoice="{{ $row->invoice_no }}"
                                                            data-finance-id="{{ $row->finance_id }}"
                                                            data-emiValue="{{ $row->lastRemaining}}"
                                                            data-deduction-date="{{ $row['due_date'] }}"
                                                            data-penalty="{{ $row->first()->penalty ?? '' }}"
                                                            data-down-payment="{{ $row->downpayment ?? '' }}"
                                                            data-customer="{{ $row->customer_name ?? '' }}"
                                                            data-customer-id="{{ $row->customer_id ?? '' }}"
                                                            data-month-duration="{{ $row->month_duration ?? '' }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#paymentModal"
                                                    >Pay
                                                    </button></td>
                <td>-</td>
            </tr>`;
                $('#purchaseTable tbody').append(newRow);
            }
        }

        // Run on document ready
        $(document).ready(function() {
            checkAndAppendPaymentRow();
        });

        $(document).on('click', '.btn-pay', function (e) {
            e.preventDefault();
            const id = $(this).data('id') ?? '';
            const invoice = $(this).data('invoice');
            const customer = $(this).data('customer');
            const customer_id = $(this).data('customer-id');
            const downpayment = $(this).data('down-payment');
            const Emi_value = parseFloat($(this).data('emivalue')) || 0;
            const month_duration = $(this).data('month-duration');
            const penalty = $(this).data('penalty');
            const finance_Id = $(this).data('finance-id');
            const deduction = parseInt($(this).data('deduction-date'));
            const deductionDate = new Date(deduction);
            // console.log(deduction);

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

            $('#modalDeductionId').val(id);
            $('#modalInvoice').val(invoice);
            $('#modalDownPayment').val(downpayment);
            $('#modalCustomer').val(customer);
            $('#modalCustomerId').val(customer_id);
            $('#modalEmiValue').html(Emi_value.toFixed(2));
            $('#EmiValue').val(Emi_value);
            $('#modalMonthDuration').html(month_duration);
            $('#modalFinanceId').val(finance_Id);

            $.ajax({
                url: '{{ route('admin.finance.deductions') }}',
                method: 'POST',
                data: {
                    finance_id: finance_Id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    const deductions = response.deductions;
                    const totalemivalue = response.totalemivalue;
                    const remaining = parseFloat(response.remaining) || 0;

                    $('#modalPaidEMI').html(deductions);
                    $('#modalPaidemiValue').html(totalemivalue);
                    $('#modalOldRemainingAmount').html(remaining.toFixed(2));
                    $('#modaloldremaining').val(remaining);

                    // Calculate amount to be paid = EMI + old remaining if available
                    let finalAmount = Emi_value;
                    if (remaining > 0) {
                        finalAmount += remaining;
                    }
                    $('#modalAmount').val(finalAmount.toFixed(2));

                    updateCalculations();
                },
                error: function () {
                    $('#deductionDetails').html(
                        '<div class="alert alert-danger">Failed to load deductions.</div>');
                }
            });

            $('#paymentModal').modal('show');
        });
        $(document).on('input change', '#modalAmount, #modalPenalty, #EmiValue, #modalTotalAmount,#modaloldremaining', updateCalculations);

        $('#modalAmount').on('input', updateCalculations);

        // function updateCalculations() {
        //     const emiValue = parseFloat($('#EmiValue').val()) || 0;
        //     const paidValue = parseFloat($('#modalAmount').val()) || 0;
        //     const penalty = parseFloat($('#modalPenalty').val()) || 0;
        //     const oldremaining = parseFloat($('#modaloldremaining').val()) || 0;
        //
        //     const remaining = emiValue - paidValue;
        //     const total = paidValue + penalty + oldremaining;
        //
        //     $('#modalRemaining').val(remaining.toFixed(2));
        //     $('#modalTotal').val(total.toFixed(2));
        //     $('#modalRemainingAmount').text(remaining.toFixed(2));
        //     $('#modalTotalAmount').text(total.toFixed(2));
        // }

        {{--$(document).on('click', '.btn-pay', function (e) {--}}
        {{--    e.preventDefault();--}}
        {{--    const id = $(this).data('id');--}}
        {{--    const invoice = $(this).data('invoice');--}}
        {{--    const customer = $(this).data('customer');--}}
        {{--    const customer_id = $(this).data('customer-id');--}}
        {{--    const downpayment = $(this).data('down-payment');--}}
        {{--    const Emi_value = $(this).data('emivalue');--}}
        {{--    const month_duration = $(this).data('month-duration');--}}
        {{--    const penalty = $(this).data('penalty');--}}
        {{--    const deduction = $(this).data('deduction-date');--}}
        {{--    const finance_Id = $(this).data('finance-id');--}}
        {{--    const todayDay = new Date().getDate();--}}

        {{--    if (todayDay > deduction) {--}}
        {{--        $('#modalPenalty').val(penalty);--}}
        {{--        $('#modalPenaltyAmount').html(penalty);--}}
        {{--        $('#modalPenaltyAmount').closest('.form-group').show();--}}
        {{--    } else {--}}
        {{--        $('#modalPenalty').val(0);--}}
        {{--        $('#modalPenaltyAmount').html('0.00');--}}
        {{--        $('#modalPenaltyAmount').closest('.form-group').hide();--}}
        {{--    }--}}

        {{--    ;--}}

        {{--    // console.log(Emi_value)--}}

        {{--    $('#modalDeductionId').val(id);--}}
        {{--    $('#modalInvoice').val(invoice);--}}
        {{--    $('#modalDownPayment').val(downpayment);--}}
        {{--    $('#modalCustomer').val(customer);--}}
        {{--    $('#modalCustomerId').val(customer_id);--}}
        {{--    $('#modalEmiValue').html(Emi_value);--}}
        {{--    $('#modalAmount').val(Emi_value);--}}
        {{--    $('#EmiValue').val(Emi_value);--}}
        {{--    $('#modalMonthDuration').html(month_duration);--}}
        {{--    $('#modalFinanceId').val(finance_Id);--}}


        {{--    $.ajax({--}}

        {{--        url: '{{ route('admin.finance.deductions') }}',--}}

        {{--        method: 'POST',--}}
        {{--        data: {--}}
        {{--            finance_id: finance_Id,--}}
        {{--            _token: '{{ csrf_token() }}'--}}
        {{--        },--}}
        {{--        success: function (response) {--}}
        {{--            const deductions = response.deductions;--}}
        {{--            const totalemivalue = response.totalemivalue;--}}
        {{--            const remaining = response.remaining;--}}
        {{--            $('#modalPaidEMI').html(deductions);--}}
        {{--            $('#modalPaidemiValue').html(totalemivalue);--}}
        {{--            $('#modalOldRemainingAmount').html(remaining);--}}
        {{--            console.log(remaining)--}}
        {{--            $('#modaloldremaining').val(remaining);--}}
        {{--            updateCalculations();--}}

        {{--        },--}}
        {{--        error: function () {--}}
        {{--            $('#deductionDetails').html(--}}
        {{--                '<div class="alert alert-danger">Failed to load deductions.</div>');--}}
        {{--        }--}}
        {{--    });--}}

        {{--    $('#paymentModal').modal('show');--}}
        {{--    updateCalculations();--}}
        {{--});--}}

        {{--$('#modalAmount').on('input', updateCalculations);--}}

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
                            <td>${item.customer_name ?? 'N/A'}</td>
                            <td>${item.phone ?? 'N/A'}</td>
                            <td>${item.city ?? 'N/A'}</td>
                            <td>
                                 <button class="btn btn-sm btn-success btn-pay" data-id="${item.id}" data-customer-id="${item.customer_id}" data-invoice="${item.invoice_no ?? ''}" data-emiValue="${item?.emi_value ?? ''}" data-deduction-date="${item?.dedication_date ?? ''}" data-penalty="${item.penalty ?? ''}" data-down-payment="${item?.downpayment ?? ''}" data-customer="${item?.customer_name ?? ''}" data-month-duration="${item?.month_duration ?? ''}" >Pay</button>
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
    <script>
        $(document).on('click', '.btn-pay', function () {
            let lastRemaining = parseFloat($('.deduction-row:last .remaining').text());
            if (lastRemaining > 0) {
                $(document).on('click', '.btn-pay', function () {
                    $.ajax({
                        url: '{{ route("deduction.pay") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',

                        },
                        success: function (response) {
                            alert("Payment successful!");
                            location.reload();
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        }

                    });

                });
            } else {
                $(document).on('click', '.btn-pay', function () {
                    let deductionId = $(this).data('id');
                    $.ajax({
                        url: '{{ route("deduction.pay") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            deduction_id: deductionId,

                        },
                        success: function (response) {
                            alert("Payment successful!");
                            location.reload();
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        }

                    });

                });
            }


        });



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

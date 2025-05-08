<table>
    <thead>
    <tr>
{{--        <th>Due Date</th>--}}
        <th>Paid Date</th>
        <th>Payment Mode</th>
        <th>Penalty</th>
        <th>Status</th>
        <th>EMI Value</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($deductions as $deduction)
        <tr id="deduction-{{ $deduction->id }}">
{{--            <td>{{ $deduction->due_date->format('Y-m-d') }}</td>--}}
            <td>{{ $deduction->paid_date ? $deduction->paid_date->format('Y-m-d') : '—' }}</td>
            <td>{{ $deduction->payment_mode ?? '—' }}</td>
            <td>{{ $deduction->penalty ?? '—' }}</td>
            <td id="status-{{ $deduction->id }}">{{ $deduction->status }}</td>
            <td>{{ $deduction->emi_value }}</td>
            <td>
                @if($deduction->status === 'Unpaid')
                    <form action="{{ route('deduction.pay', $deduction->id) }}" method="POST">
                        @csrf
                        <button type="submit">Pay</button>
                    </form>
                @else
                    —
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    function payEMI(deductionId) {
        let paymentMode = prompt("Enter payment mode (e.g., Cash, UPI, Card):");
        let penalty = prompt("Enter penalty if any (or leave blank):");

        if (!paymentMode) {
            alert("Payment mode is required.");
            return;
        }

        fetch(`/deduction/pay/${deductionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                payment_mode: paymentMode,
                penalty: penalty,
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update row in table
                    document.getElementById(`status-${deductionId}`).innerText = "Paid";
                    document.getElementById(`deduction-${deductionId}`).querySelector("td:nth-child(3)").innerText = data.paid_date;
                    document.getElementById(`deduction-${deductionId}`).querySelector("td:nth-child(4)").innerText = paymentMode;
                    document.getElementById(`deduction-${deductionId}`).querySelector("td:nth-child(5)").innerText = penalty || '—';
                    document.getElementById(`deduction-${deductionId}`).querySelector("td:nth-child(8)").innerText = '—'; // remove Pay button
                } else {
                    alert("Payment failed. Try again.");
                }
            });
    }
</script>

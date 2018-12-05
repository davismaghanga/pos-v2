<table class="table table-responsive" id="table">
    <thead>
    <tr>
        <th>No</th>
        <th>Customer</th>
        <th>Phone number</th>
        <th>Sale Id</th>
        <th>Cashier</th>
        <th>Amount</th>
        <th>Discount</th>
        <th>Paid</th>
        <th>Balance</th>
        <th>Mode</th>
    </tr>
    </thead>

    <tbody>

    @php($totalamount=0)
    @php($totalpaid=0)
    @php($totalbalance=0)

    @foreach($receipts as $key=>$receipt)
        @php($sale=$receipt->Sale)

        @php($totalamount+=$receipt->amount)
        @php($totalbalance+=$receipt->balance)

        @if($sale!=null)
            @php($saleamount=$sale->amount - $sale->discount + $sale->tax_ex)
        @else
            @php($saleamount=0)
        @endif
        @php($paid=$saleamount+$receipt->balance)
        @php($totalpaid+=$paid)


        <tr>
            <td>{{($key+1)}}</td>
            <td>{{($receipt->Customer!=null)?$receipt->Customer->name:"undefined"}}</td>
            <td>{{($receipt->Customer!=null)?$receipt->Customer->phone:"undefined"}}</td>
            <td>{{$receipt->sale}}</td>
            <td>{{($receipt->Cashier!=null)?$receipt->Cashier->name:"undefined"}}</td>
            <td>{{$receipt->amount}}</td>
            <td>{{($receipt->Sale!=null)?$receipt->Sale->discount:"0"}}</td>
            <td>{{$paid}}</td>
            <td>{{$receipt->balance}}</td>
            <td>
                @if($receipt->channel==1)
                    Cash
                @elseif($receipt->channel==2)
                    {{$receipt->code}}
                @else
                    {{$receipt->code}}
                @endif
            </td>
        </tr>
    @endforeach

    </tbody>
    <thead>
    <tr>
        <th></th>
        <th>Totals</th>
        <th></th>
        <th></th>
        <th></th>
        <th>{{$totalamount}}</th>
        <th></th>
        <th>{{$totalpaid}}</th>
        <th>{{$totalbalance}}</th>
        <th></th>
    </tr>
    </thead>
</table>
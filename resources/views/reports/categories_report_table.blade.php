<table class="table" id="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
    @php($qtotal=0)
    @php($stotal=0)
        @foreach($sales_items as $key=>$sale_item)
            @php($product=$sale_item->Product)
            @if($sale_item->is_service)
            @php($item_price=(($product!=null)?$product->charge:0)*$sale_item->quantity)
            @else
            @php($item_price=(($product!=null)?$product->price:0)*$sale_item->quantity)
            @endif
            <tr>
                <td>{{($key+1)}}</td>
                <td>{{$sale_item->created_at}}</td>
                <td>
                        {{($product!=null)?$product->name:'Undefined'}}
                </td>
                <td>{{$sale_item->quantity}}</td>
                <td>{{$item_price}}</td>
            </tr>
            @php($qtotal=($qtotal+($sale_item->quantity)))
            @php($stotal=$stotal+$item_price)
            @endforeach
    </tbody>

    <thead>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th>{{$sales_items->sum('quantity')}}</th>
        <th>{{$stotal}}</th>
    </tr>
    </thead>
</table>
<div class="w-3/5 bg-white shadow-lg">
    <div class="w-full h-0.5 bg-indigo-500"></div>
    <div class="flex justify-between p-4">
        <div>
            <h6 class="font-bold">Order Date : <span class="text-sm font-medium">{{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y h:i a') }}</span></h6>
            <h6 class="font-bold">Order ID : <span class="text-sm font-medium">{{ $order->uid }}</span></h6>
            <h6 class="font-bold">Status : <span class="text-sm font-medium">{{ $order->status }}</span></h6>
        </div>
        <div class="w-40">
            <address class="text-sm">
                <span class="font-bold"> Billed To : </span><br>
                {{ $order->firstname }} {{ $order->lastname }}<br>
                {{ $order->line_1_address }}<br>
                {{ $order->city }}, {{ $order->zipcode }}<br>
            </address>
        </div>
        <div></div>
    </div>
    <div class="flex justify-center p-4">
        <div class="w-full border-b border-gray-200 shadow">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <!--  <th class="px-4 py-2 text-xs text-gray-500 ">
                            #
                        </th> -->
                        <th class="px-4 py-2 text-xs text-left text-gray-500 ">
                            Product Name
                        </th>
                        <th class="px-4 py-2 text-xs text-left text-gray-500 ">
                            Quantity
                        </th>
                        <th class="px-4 py-2 text-xs text-left text-gray-500 ">
                            Rate
                        </th>
                        <th class="px-4 py-2 text-xs text-left text-gray-500 ">
                            Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($order->products as $op => $item)
                    <tr class="whitespace-nowrap">
                        <!--  <td class="px-6 py-4 text-sm text-gray-500">
                            1
                        </td> -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $item->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ $item->quantity }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $currency['symbol'] . $item->price }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $currency['symbol'] . ( $item->price * $item->quantity ) }}
                        </td>
                    </tr>
                    @endforeach
                    
                    <tr class="">
                        <td colspan="2"></td>
                        <td class="text-sm font-bold">Sub Total</td>
                        <td class="text-sm font-bold px-6 tracking-wider"><b>{{ $currency['symbol'] . $order->subtotal() }}</b></td>
                    </tr>
                    <!--end tr-->
                    @foreach($order->charges as $oc => $item)
                    <tr>
                        <th colspan="2"></th>
                        <td class="text-sm font-bold"><b>{{ $item->name }}</b></td>
                        <td class="text-sm font-bold px-6"><b>{{ $currency['symbol'] . $item->price }}</b></td>
                    </tr>
                    @endforeach
                    <!--end tr-->
                    <tr class="text-white bg-gray-800">
                        <th colspan="2"></th>
                        <td class="text-sm font-bold"><b>Total</b></td>
                        <td class="text-sm font-bold px-6"><b>{{ $currency['symbol'] . $order->total() }}</b></td>
                    </tr>
                    <!--end tr-->

                </tbody>
            </table>
        </div>
    </div>
    <!--  <div class="flex justify-between p-4">
        <div>
            <h3 class="text-xl">Terms And Condition :</h3>
            <ul class="text-xs list-disc list-inside">
                <li>All accounts are to be paid within 7 days from receipt of invoice.</li>
                <li>To be paid by cheque or credit card or direct payment online.</li>
                <li>If account is not paid within 7 days the credits details supplied.</li>
            </ul>
        </div>
        <div class="p-4">
            <h3>Signature</h3>
            <div class="text-4xl italic text-indigo-500">AAA</div>
        </div>
    </div> -->
    <div class="w-full h-0.5 bg-indigo-500"></div>

   

</div>
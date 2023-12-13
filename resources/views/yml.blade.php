
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
@php
    $date = now()->format('Y-m-d\TH:iP');
@endphp

<yml_catalog date="{{$date}}" >
        <shop>
            <categories>
                @foreach($categories as $category)
                <category id="{{$category->id}}">{{$category->name}}</category>
                @endforeach
            </categories>

            <name>Название вашего магазина</name>
            <offers>
                @foreach ($products as $product)
                    <offer id="{{ $product->id }}" available="{{ $product->availability == 0 ? 'false' : 'true' }}">
                        <version>1.0</version>
                        <categoryId>{{$product->category_id}}</categoryId>
                        <price>{{ $product->price->amount / 100  }}</price>
                        <currencyId>RUB</currencyId>
                        <picture>{{ $product->thumb_photo . ".jpeg" }}</picture>
                        <name>{{ $product->title }}</name>
                        <description><![CDATA[{{ $product->description }}]]></description>
                    </offer>
                @endforeach
            </offers>
        </shop>
    </yml_catalog>

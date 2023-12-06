
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<yml_catalog  >
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
                        <categoryId>{{$product->category_id}}</categoryId>
                        <price>{{ $product->price->amount }}</price>
                        <currencyId>RUB</currencyId>
                        <picture>{{ $product->thumb_photo }}</picture>
                        <name>{{ $product->title }}</name>
                        <description><![CDATA[{{ $product->description }}]]></description>
                    </offer>
                @endforeach
            </offers>
        </shop>
    </yml_catalog>

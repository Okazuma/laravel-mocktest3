<div class="user--products">
    <div class="button-group">
        <button wire:click="showSelling" class="{{ $viewType === 'selling' ? 'active' : '' }}">出品した商品</button>
        <button wire:click="showBought" class="{{ $viewType === 'bought' ? 'active' : '' }}">購入した商品</button>
    </div>

    <div class="full-width-line"></div>

    <div class="product-list">
        @if($viewType === 'selling')
            @if($sellingItems->isEmpty())
                <p>まだ出品した商品はありません。</p>
            @else
                <div class="sell--items">
                    @foreach($sellingItems as $item)
                        <a class="sell--item__image" href="{{route('detail', $item->id)}}">
                            @if ($item->item_image)
                                @if (config('filesystems.default') === 's3')
                                    <img src="{{ Storage::disk('s3')->url($item->item_image) }}" alt="">
                                @else
                                    <img src="{{ asset('storage/' . $item->item_image) }}" alt="No image">
                                @endif
                            @else
                                <img src="" alt="No image">
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        @else
            @if($boughtItems->isEmpty())
                <p>まだ購入した商品はありません。</p>
            @else
                <div class="sell--items">
                    @foreach($boughtItems as $item)
                        <a class="sell--item__image" href="{{route('detail', $item->id)}}">
                            @if ($item->item_image)
                                @if (config('filesystems.default') === 's3')
                                    <img src="{{ Storage::disk('s3')->url($item->item_image) }}" alt="">
                                @else
                                    <img src="{{ asset('storage/' . $item->item_image) }}" alt="">
                                @endif
                            @else
                                <img src="" alt="No image">
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
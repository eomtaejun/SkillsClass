
    <div class="container" style="min-height: calc(100vh - 120px);">
        <!-- 인기상품 -->
        <section class="sales">
            <p class="mb-5 fx-6 font-bold text-black text-center">인기상품</p>

            <div class="row">
            <?php foreach($products as $product):?>
                <div class="col-10 col-md-4 mb-5">
                    <div class="d-flex flex-column justify-content-between align-items-center h-100">
                        <div class="item rank mb-3">
                            <div class="badges d-flex justify-content-start align-items-center col-gap-2">
                                <p class="mb-0 px-2 py-1 rounded-3 text-light bg-red">인기상품</p>
                                <p class="mb-0 px-2 py-1 rounded-3 text-light bg-purple"><?=$product->category?></p>
                                <p class="mb-0 px-2 py-1 rounded-3 text-light bg-green">할인상품</p>
                            </div>

                            <img src="/common/products/<?=$product->category?>/<?=$product->img?>" alt="product image" title="product image" class="fit-cover rounded-3 mb-2">
                            <p class="mb-1 font-bold text-black fx-2 word-wrap"><?=$product->title?></p>
                            <p class="mb-1 text-dark fx-n1 word-wrap"><?=$product->detail?></p>
                            <p class="mb-0 fx-1 text-secondary text-cancel word-wrap"><?=number_format($product->price)?>원</p>
                            <p class="mb-0 fx-3 font-bold text-red word-wrap">
                                <?php
                                    switch($product->discount){
                                        case "10000":
                                            echo number_format($product->price-10000) . '원';
                                            break;
                                        case "10%":
                                            echo number_format($product->price*0.9) . '원';
                                            break;
                                        case "30%":
                                            echo number_format($product->price*0.7) . '원';
                                            break;
                                        default:
                                            echo number_format($product->price) . '원';
                                    }
                                ?>
                            </p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center col-gap-2 w-100">
                            <button type="button" name="cart" class="btn btn-outline-dark py-2 fx-n1 border-gray w-50 rounded-3" data-id="<?=$product->id?>">장바구니담기</button>
                            <button type="button" class="btn btn-dark py-2 fx-n1 border-gray w-50 rounded-3">구매하기</button>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
                
            </div>
        </section>
    </div>

    <script>
        var cart=new Cart();
    </script>
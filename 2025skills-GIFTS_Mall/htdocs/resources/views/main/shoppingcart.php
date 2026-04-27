
    <div class="container" style="min-height: calc(100vh - 120px);">
        <!-- 인기상품 -->
        <section class="cart">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="border rounded-3 px-3 pt-3">
                        <?php foreach($products as $product):?>
                            <div class="item mb-3">
                                <div class="row">
                                    <div class="col-4"><img src="/common/products/<?=$product->category?>/<?=$product->img?>" alt="product image" title="product iamge" class="fit-cover rounded-3"></div>

                                    <div class="col-8">
                                        <p class="mb-0 fx-2 font-bold text-black word-wrap"><?=$product->title?></p>
                                        <p class="mb-0 fx-2 font-bold text-dark word-wrap">
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
                                        <div class="d-flex justify-content-start align-items-center col-gap-2 my-1">
                                            <input type="number" name="quantity" class="form-control w-25" value="<?=$product->quantity?>" min="1" data-id="<?=$product->id?>">
                                            <p class="mb-0 text-black">개</p>
                                        </div>
                                        <p class="mb-0 text-red font-bold fx-3 text-end total-price-<?=$product->id?>">
                                            <?php
                                                switch($product->discount){
                                                    case "10000":
                                                        echo '총 ' . number_format(($product->price-10000)*$product->quantity) . '원';
                                                        break;
                                                    case "10%":
                                                        echo '총 ' . number_format(($product->price*0.9)*$product->quantity) . '원';
                                                        break;
                                                    case "30%":
                                                        echo '총 ' . number_format(($product->price*0.7)*$product->quantity) . '원';
                                                        break;
                                                    default:
                                                        echo '총 ' . number_format(($product->price)*$product->quantity) . '원';
                                                }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="border rounded-3 p-3">
                        <p class="mb-1 pb-1 border-bottom fx-4 font-bold text-black">결제 정보</p>

                        <div class="d-flex justify-content-between align-items-center py-3 my-1">
                            <p class="mb-0 fx-1 text-dark">전체 결제금액</p>
                            <p class="mb-0 fx-3 text-red font-bold all-price">0원</p>
                        </div>

                        <div class="d-flex justify-content-end align-items-center py-2 border-top">
                            <button type="button" class="btn btn-primary">구매하기</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        var cart=new Cart();
    </script>
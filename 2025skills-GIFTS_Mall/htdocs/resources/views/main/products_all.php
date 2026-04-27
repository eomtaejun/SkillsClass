
    <div class="container" style="min-height: calc(100vh - 120px);">
        <!-- 비디오 -->
        <div class="video-container position-relative overflow-hidden my-5 rounded-2">
            <div class="video">
                <video src="/common/video/AD.mp4" class="fit-cover d-block"></video>
            </div>

            <div class="controls d-flex justify-content-between align-items-center p-1 bg-dark">
                <div class="left d-flex justify-content-start align-items-center col-gap-2">
                    <div class="video-control" title="재생">
                        <i class="fa fa-play"></i>
                    </div>

                    <div class="video-control" title="일시정지">
                        <i class="fa fa-pause"></i>
                    </div>

                    <div class="video-control" title="정지">
                        <i class="fa fa-square"></i>
                    </div>

                    <div class="video-control" title="10초 되감기">
                        <i class="fa fa-backward"></i>
                    </div>

                    <div class="video-control" title="10초 빨리감기">
                        <i class="fa fa-forward"></i>
                    </div>
                </div>

                <div class="right d-flex justify-content-start align-items-center col-gap-2">
                    <div class="video-control" title="-0.1배속">
                        <p class="mb-0 fx-n1 text-light playbackrate">1x</p>
                    </div>

                    <div class="video-control" title="-0.1배속">
                        <i class="fa fa-minus"></i>
                    </div>

                    <div class="video-control" title="+0.1배속">
                        <i class="fa fa-plus"></i>
                    </div>

                    <div class="video-control" title="배속 초기화">
                        <i class="fa fa-refresh"></i>
                    </div>

                    <div class="video-control" title="컨트롤러 숨기기">
                        <i class="fa fa-eye-slash"></i>
                    </div>

                    <div class="video-control" title="반복재생 꺼짐">
                        <i class="fa fa-repeat"></i>
                    </div>

                    <div class="video-control" title="자동재생 꺼짐">
                        <i class="fa fa-retweet"></i>
                    </div>
                </div>
            </div>

            <div class="controls-sub d-flex justify-content-end align-items-center p-1 hide">
                <div class="video-control" title="컨트롤러 보이기">
                    <i class="fa fa-eye"></i>
                </div>
            </div>
        </div>

        <!-- 전체상품 -->
        <section class="sales">
            <p class="mb-5 fx-6 font-bold text-black text-center">전체상품</p>
            <?php $categories=["건강식품", "디지털", "팬시", "향수", "헤어케어"]?>
                <?php foreach($categories as $category):?>
                    <div class="py-5">
                        <div class="d-flex justify-content-start align-items-center col-gap-2 mb-3">
                            <p class="mb-0 text-dark font-bold fx-4"><?=$category?></p>
                        </div>
                        <div class="row">
                            <?php foreach(array_filter($products, fn($value)=>$value->category===$category) as $product):?>
                                <div class="col-10 col-md-4 mb-5">
                                    <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                        <div class="item <?=$product->discount ? "rank" : ""?> mb-3">
                                            <div class="badges <?=$product->discount ? "d-flex" : "d-none"?> justify-content-start align-items-center col-gap-2">
                                                <p class="mb-0 px-2 py-1 rounded-3 text-light bg-red">인기상품</p>
                                                <p class="mb-0 px-2 py-1 rounded-3 text-light bg-green">할인상품</p>
                                            </div>

                                            <img src="/common/products/<?=$product->category?>/<?=$product->img?>" alt="product image" title="product image" class="fit-cover rounded-3 mb-2">
                                            <p class="mb-1 font-bold text-black fx-2 word-wrap"><?=$product->title?></p>
                                            <p class="mb-1 text-dark fx-n1 word-wrap"><?=$product->detail?></p>
                                            <p class="mb-0 fx-1 text-secondary text-cancel word-wrap <?=$product->discount ? "" : "d-none"?>"><?=number_format($product->price)?>원</p>
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
                    </div>
                <?php endforeach;?>
        </section>

        <section>
            <div class="w-100 border rounded-3 p-5 d-flex justify-content-center align-items-center col-gap-3">
                <i class="fa fa-user-times text-coral fx-6"></i>
                <p class="mb-0 fx-3 font-bold text-black">회원가입 없이 상품을 구매하실 수 있습니다!</p>
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#orderModal">비회원주문</button>
            </div>
        </section>

        <!-- 비회원 주문 모달 -->
        <div class="modal fade" id="orderModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <p class="mb-0 fx-4 font-bold text-black">비회원 주문</p>
                        <i class="fa fa-close text-secondary fx-n1 p-2" style="cursor: pointer;" data-bs-dismiss="modal"></i>
                    </div>

                    <div class="modal-body bg-gray">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="bg-white rounded-3 shadow-lg p-3">
                                    <p class="mb-1 fx-3 font-bold text-black">회원정보영역</p>
                                    <p class="mb-0 fx-1 font-500 text-dark userid">비회원 ID: k2Ai11</p>
                                </div>
                            </div>
                            
                            <div class="col-6 mb-3">
                                <div class="bg-white rounded-3 shadow-lg p-3">
                                    <p class="mb-1 fx-3 font-bold text-black">전시영역</p>
                                    <div class="row products">
                                        
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6 mb-3">
                                <div class="bg-white rounded-3 shadow-lg p-3">
                                    <p class="mb-1 fx-3 font-bold text-black">주문영역</p>
                                    <div class="row orders">

                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="bg-white rounded-3 shadow-lg p-3">
                                    <p class="mb-1 fx-3 font-bold text-black">결제영역</p>
                                    <div class="d-flex justify-content-end align-items-center col-gap-3">
                                        <p class="mb-0 fx-2 font-bold text-red all-price">총 0원</p>
                                        <button type="button" class="btn btn-primary disabled" id="order" data-bs-dismiss="modal">주문하기</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 알림창 -->
        <div class="modal fade" id="alert">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-gray">
                    <div class="modal-body">
                        <p class="mb-0 fx-3 font-bold text-dark text-center">방금 비회원 XXXXXX님이 ㅇㅇㅇㅇ원을 결제하셨습니다!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var orders=new Orders();
        var video=new Video();
        var cart=new Cart();
    </script>
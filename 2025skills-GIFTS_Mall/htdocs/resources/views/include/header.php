<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="/common/js/jquery-3.7.1.js"></script>
    <script src="/common/bootstrap-5.1.3-dist/bootstrap-5.1.3-dist/js/bootstrap.js"></script>
    <script src="/common/js/script.js"></script>
    <link rel="stylesheet" href="/common/bootstrap-5.1.3-dist/bootstrap-5.1.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/common/fontawesome/css/font-awesome.css">
    <link rel="stylesheet" href="/common/css/style.css">
</head>
<body>
    <!-- 로딩 애니메이션 -->
    <!-- <div class="loading w-100 h-100">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div> -->

    <header class="bg-white border-bottom">
        <div class="container h-100">
            <div class="h-50 w-100 d-flex justify-content-between align-items-center">
                <div class="logo h-75 tran">
                    <a href="/" class="w-100 h-100"><img src="/common/icnos/logo.png" alt="logo" title="GIFTS:Mall" class="fit-cover"></a>
                </div>

                <div class="util d-flex justify-content-end align-items-center col-gap-2">
                    <?php if(!isset($_SESSION['user'])):?>
                        <a href="#" class="fx-n1 text-dark" data-bs-toggle="modal" data-bs-target="#loginModal">로그인</a>
                        <a href="#" class="fx-n1 text-dark" data-bs-toggle="modal" data-bs-target="#joinModal">회원가입</a>
                    <?php else:?>
                        <p class="mb-0 fx-n1 text-dark"><?=$_SESSION['user']->name?> 님</p>
                        <a href="/users/logout_action" class="fx-n1 text-dark">로그아웃</a>
                    <?php endif;?>
                    <a href="/shoppingcart" class="fx-n1 text-dark">장바구니</a>
                </div>
            </div>

            <div class="depth-1 h-50 w-100 d-flex justify-content-center align-items-center">
                <div class="col p-0 h-100">
                    <a href="/introduction" class="text-black h-100 w-100">소개</a>
                </div>
                <div class="col p-0 h-100">
                    <a href="/products/all" class="text-black h-100 w-100">판매상품</a>
                </div>
                <div class="col p-0 h-100">
                    <a href="#" class="text-black h-100 w-100">가맹점</a>
                </div>
                <div class="col p-0 h-100">
                    <a href="/shoppingcart" class="text-black h-100 w-100">장바구니</a>
                </div>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']->admin==true):?>
                    <div class="col p-0 h-100">
                        <a href="#" class="text-black h-100 w-100">관리자</a>
                    </div>
                <?php endif;?>
            </div>
        </div>

        <div class="depth-2 border-bottom bg-white tran">
            <div class="container h-100">
                <div class="d-flex justify-content-center align-items-center w-100 h-100">
                    <div class="col p-0 h-100">
                        <p class="mb-0 text-secondary fx-n1 text-center my-3">-</p>
                    </div>
                    <div class="col p-0 h-100 border-start">
                        <a href="/products/all" class="text-secondary fx-n1 text-center my-3">전체상품</a>
                        <a href="/products/popularity" class="text-secondary fx-n1 text-center my-3">인기상품</a>
                    </div>
                    <div class="col p-0 h-100 border-start">
                        <p class="mb-0 text-secondary fx-n1 text-center my-3">-</p>
                    </div>
                    <div class="col p-0 h-100 border-start">
                        <p class="mb-0 text-secondary fx-n1 text-center my-3">-</p>
                    </div>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']->admin==true):?>
                        <div class="col p-0 h-100 border-start">
                            <a href="/admins/notifications" class="text-secondary fx-n1 text-center my-3">공지사항관리</a>
                            <a href="/admins/products" class="text-secondary fx-n1 text-center my-3">판매상품관리</a>
                            <a href="/admins/users" class="text-secondary fx-n1 text-center my-3">회원관리</a>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </header>

    <!-- 헤더 공백 -->
    <div style="height: 120px;"></div>

    <?php if(isset($this->msg) && isset($this->msgType)):?>
        <div class="container my-3">
            <div class="mb-0 alert alert-<?=$this->msgType?>"><?=$this->msg?></div>
        </div>
    <?php endif;?>

    <!-- 회원가입 -->
    <div class="modal fade" id="joinModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <p class="mb-0 fx-4 font-bold text-black">회원가입</p>
                    <i class="fa fa-close text-secondary fx-n1 p-2" style="cursor: pointer;" data-bs-dismiss="modal"></i>
                </div>

                <div class="modal-body">
                    <form action="/users/join_action" method="post" id="join_form" class="w-100 py-5">
                        <div class="d-flex flex-column justify-content-center align-items-center row-gap-4" class="w-100">
                            <input type="text" name="join_userid" id="join_userid" class="my-input w-50" placeholder="ID">
                            <input type="password" name="join_userpass" id="join_userpass" class="my-input w-50" placeholder="Password">
                            <input type="text" name="join_username" id="join_username" class="my-input w-50" placeholder="이름">
                            <input type="email" name="join_email" id="join_email" class="my-input w-50" placeholder="이메일">
                            <button type="submit" class="mt-4 bg-coral text-light fx-n1 py-2 rounded-pill w-50 tran">회원가입</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 로그인 -->
    <div class="modal fade" id="loginModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <p class="mb-0 fx-4 font-bold text-black">로그인</p>
                    <i class="fa fa-close text-secondary fx-n1 p-2" style="cursor: pointer;" data-bs-dismiss="modal"></i>
                </div>

                <div class="modal-body">
                    <form action="/users/login_action" method="post" id="login_form" class="w-100 py-5">
                        <div class="d-flex flex-column justify-content-center align-items-center row-gap-4" class="w-100">
                            <input type="text" name="login_userid" id="login_userid" class="my-input w-50" placeholder="ID">
                            <input type="password" name="login_userpass" id="login_userpass" class="my-input w-50" placeholder="PW">
                            <button type="submit" class="mt-4 bg-coral text-light fx-n1 py-2 rounded-pill w-50 tran">로그인</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var users=new Users();

        // var test=new Test();
    </script>
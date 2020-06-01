<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>내집꾸미기</title>
    <script src="/js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script>
        $(function(){
            $(".custom-file-input").on("change" , function(){
                if(this.files.length > 0) {
                    $(this).siblings(".custom-file-label").text(this.files[0].name);
                }
            });

            $("[data-target='#join-modal']").on("click", function(){
                let canvas = document.querySelector("#join-modal canvas");
                let ctx = canvas.getContext("2d");
                ctx.font = "50px 나눔고딕, sans-serif";

                ctx.clearRect(0, 0, canvas.width, canvas.height);

                let text = Math.random().toString(36).substr(2, 5);
                let ms = ctx.measureText(text);
                ctx.fillText(text, canvas.width / 2 - ms.width / 2, canvas.height / 2 + 25);

                $("#captcha").val(text);
            });


            $("#join_photo").on("change", function(){
                if(this.files.length === 0) return;

                new Promise(res => {
                    let file = this.files[0];
                    let reader = new FileReader();
                    reader.onload = function(){
                        res(reader.result);
                    };
                    reader.readAsDataURL(file);
                }).then(url => new Promise( res => {
                    let image = new Image();
                    image.src = url;
                    image.onload = () => res(image);
                })).then(img => {
                    let canvas = document.createElement("canvas");
                    canvas.width = canvas.height = 64;

                    let x = img.width / 2 + 32;
                    let y = img.height / 2 + 32;

                    let width = img.width;
                    let height = img.height;
                    if(width > height) {
                        width = 64 * width / height;
                        height = 64;
                    } else {
                        height = 64 * height / width;
                        width = 64;
                    }

                    let ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, width, height);
                    
                    let url = canvas.toDataURL('image/jpeg');
                    $("#base64_photo").val(url);
                });
            });
        });
    </script>
</head>
<body>
    <!-- 헤더 영역 -->
    <div id="header">
        <div class="container h-100">
            <div class="d-between h-100">
                <div class="d-flex align-items-center h-100">
                    <a href="/">
                        <img src="/images/logo.svg" alt="내집꾸미기" title="내집꾸미기" height="60">
                    </a>
                    <div class="nav ml-5 d-lg-flex d-none">
                        <a href="/">홈</a>
                        <a href="/online-party">온라인 집들이</a>
                        <a href="/store">스토어</a>
                        <a href="/specialists">전문가</a>
                        <a href="/estimates">시공 견적</a>
                    </div>
                </div>
                <div class="h-100 d-flex align-items-center">
                    <div class="auth d-lg-flex d-none">
                        <?php if(user()):?>
                            <span class="fx-n1 text-gold mr-3"><?=user()->user_name?>(<?=user()->user_id?>)</span>
                            <a href="/logout">로그아웃</a>
                        <?php else: ?>
                            <a href="#" data-toggle="modal" data-target="#login-modal">로그인</a>
                            <a href="#" data-toggle="modal" data-target="#join-modal">회원가입</a>
                        <?php endif;?>
                    </div>
                    <div class="menu-btn d-lg-none">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="menu d-lg-none">
                        <div class="m-nav">
                            <a href="/">홈</a>
                            <a href="/online-party">온라인 집들이</a>
                            <a href="/store">스토어</a>
                            <a href="/specialists">전문가</a>
                            <a href="/estimates">시공 견적</a>
                        </div>
                        <div class="m-auth">
                            <?php if(user()):?>
                                <span class="fx-n1 text-gold mr-3"><?=user()->user_name?>님</span>
                                <a href="/logout">로그아웃</a>
                            <?php else: ?>
                                <a href="#" data-toggle="modal" data-target="#login-modal">로그인</a>
                                <a href="#" data-toggle="modal" data-target="#join-modal">회원가입</a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / 헤더영역 -->

    <!-- 로그인 폼 -->
    <div id="login-modal" class="modal fade">
        <div class="modal-dialog">
            <form action="/sign-in" method="post" class="modal-content">
                <div class="modal-body px-4 py-5">
                    <div class="px-3 text-center mb-5">
                        <div class="title">SIGN UP</div>
                    </div>
                    <div class="form-group">
                        <label for="login_id">아이디</label>
                        <input type="text" id="login_id" name="user_id" class="form-control" placeholder="아이디를 입력하세요">
                    </div>
                    <div class="form-group">
                        <label for="login_pw">비밀번호</label>
                        <input type="password" id="login_pw" class="form-control" name="password" placeholder="비밀번호를 입력하세요">
                    </div>
                    <div class="form-group mt-4">
                        <button class="black-btn w-100 fx-n2 py-2">로그인</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /로그인 폼 -->

    <!-- 회원가입 폼 -->
    <div id="join-modal" class="modal fade">
        <div class="modal-dialog">
            <form action="/sign-up" method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-body px-4 py-5">
                    <div class="px-3 text-center mb-5">
                        <div class="title">SIGN UP</div>
                    </div>
                    <div class="form-group">
                        <label for="join_id">아이디</label>
                        <input type="text" id="join_id" name="user_id" class="form-control" placeholder="아이디를 입력하세요">
                    </div>
                    <div class="form-group">
                        <label for="join_pw">비밀번호</label>
                        <input type="password" id="join_pw" class="form-control" name="password" placeholder="비밀번호를 입력하세요">
                    </div>
                    <div class="form-group">
                        <label for="join_name">이름</label>
                        <input type="text" id="join_name" name="user_name" class="form-control" placeholder="이름를 입력하세요">
                    </div>
                    <div class="form-group">
                        <label for="join_photo">사진</label>
                        <input type="text" id="base64_photo" name="photo" hidden>
                        <div class="custom-file">
                            <input type="file" id="join_photo" class="custom-file-input">
                            <label for="join_photo" class="custom-file-label">파일을 업로드 하세요</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="captcha" name="captcha">
                        <canvas width="400" height="100"></canvas>
                        <input type="text" class="mt-3 form-control" name="input_captcha" placeholder="상단의 문자열을 입력해 주세요">
                    </div>
                    <div class="form-group mt-4">
                        <button class="black-btn w-100 fx-n2 py-2">가입하기</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /회원가입 폼 -->
<!-- 비주얼 영역 -->
<div id="visual" class="sub">
    <div class="images">
        <img src="/images/slide/1.jpg" alt="슬라이드 이미지" title="슬라이드 이미지">
    </div>
    <div class="position-center text-center mt-4">
        <div class="fx-7 text-white font-weight-bolder segoe mt-n4">STORE</div>
        <p class="text-gray fx-n3 mb-5 mb-lg-0">당신의 공간을 채워줄 멋진 인테리어를 소개합니다</p>
    </div>
</div>
<!-- /비주얼 영역 -->

<!-- 장바구니 영역 -->
<div class="bg-gray">
    <div class="container padding pb-5">
        <div class="sticky-top bg-gray pt-4">
            <div>
                <span class="text-muted">장바구니</span>
                <div class="title mt-n1">CART</div>
            </div>
            <div class="table-head mt-2">
                <div class="cell-50">상품정보</div>
                <div class="cell-15">가격</div>
                <div class="cell-10">수량</div>
                <div class="cell-15">합계</div>
                <div class="cell-10">+</div>
            </div>
        </div>
        <div id="cart-list"></div>
        <div class="d-between mt-4">
            <div class="px-3">
                <span class="fx-n1">총 합계</span>
                <span class="fx-3 ml-3 text-gold total-price">200,000</span>
                <span class="ml-1 text-muted">원</span>
            </div>
            <button class="border-btn fx-n2 py-2" data-toggle="modal" data-target="#buy-modal">구매하기</button>
        </div>
    </div>
</div>
<!-- /장바구니 영역 -->

<!-- 스토어 영역 -->
<div class="container padding pt-5">
    <div class="sticky-top bg-white border-bottom pb-3 pt-4">
        <div class="d-between align-items-end">
            <div>
                <span class="text-muted">인테리어 상점</span>
                <div class="title">STORE</div>
            </div>
            <div class="d-flex align-items-center pr-5">
                <input type="checkbox" id="open-cart" hidden checked>
                <div class="search">
                    <span class="icon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="search-bar" placeholder="검색어를 입력하세요">
                    <div class="plus">
                    </div>
                </div>
                <label for="open-cart" class="ml-3 icon-cart text-gold">
                    <i class="fa fa-shopping-cart fa-lg"></i>
                </label>
                <div id="drop-area">
                    <div class="text-center text-white w-100 h-100">
                        <div class="success bg-success position-center">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                        <div class="error bg-danger position-center">
                            <i class="fa fa-times fa-3x"></i>
                            <p class="mt-2 fx-n3 text-nowrap">이미 장바구니에 담긴 상품입니다.</p>
                        </div>
                        <div class="normal position-center">
                            <i class="fa fa-shopping-cart fa-3x"></i>
                            <p class="mt-2 fx-n3 text-nowrap">이곳에 상품을 넣어주세요.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="store-list" class="row mt-4">
    </div>
</div>
<!-- /스토어 영역 -->

<!-- 구매하기 모달 -->
<div id="buy-modal" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-body px-4 pt-5 pb-4">
                <div class="px-3 text-center mb-5">
                    <div class="title">PURCHASE</div>
                </div>
                <div class="form-group">
                    <label for="username">구매자 명</label>
                    <input type="text" id="username" class="form-control" placeholder="이름을 입력하세요" required>
                </div>
                <div class="form-group">
                    <label for="address">배송 주소</label>
                    <input type="text" id="address" class="form-control" placeholder="주소를 입력하세요" required>
                </div>
                <div class="form-group mt-4">
                    <button class="black-btn w-100 fx-n2 py-2">구매하기</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /구매하기 모달 -->

<!-- 구매 내역 모달 -->
<div id="view-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img alt="구매내역">
            </div>
        </div>
    </div>
</div>
<!-- /구매 내역 모달 -->

<script src="js/Product.js"></script>
<script src="js/App.js"></script>
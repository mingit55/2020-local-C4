<!-- 비주얼 영역 -->
<div id="visual" class="sub">
    <div class="images">
        <img src="/images/slide/1.jpg" alt="슬라이드 이미지" title="슬라이드 이미지">
    </div>
    <div class="position-center text-center mt-4">
        <div class="fx-7 text-white font-weight-bolder segoe mt-n4">ESTIMATE</div>
        <p class="text-gray fx-n3 mb-5 mb-lg-0">상상으로 그리던 인테리어, 지금 현실로 이루세요</p>
    </div>
</div>
<!-- /비주얼 영역 -->

<!-- 요청 영역 -->
<div class="bg-gray<?=!user()->auth ? ' full-height': ''?>">
    <div class="container padding pb-5">
        <div class="sticky-top bg-gray pt-4">
            <div class="d-between pr-4">
                <div>
                    <span class="text-muted">시공 견적 요청</span>
                    <div class="title mt-n1">REQUESTS</div>
                </div>
                <button class="border-btn fx-n1 px-5" data-toggle="modal" data-target="#request-modal">견적 요청</button>
            </div>
            <div class="table-head mt-2">
                <div class="cell-10">상태</div>
                <div class="cell-30">내용</div>
                <div class="cell-15">요청자</div>
                <div class="cell-15">시공일</div>
                <div class="cell-15">견적 개수</div>
                <div class="cell-15">+</div>
            </div>
        </div>
        <div class="list">
            <?php foreach($requests as $request) :?>
            <div class="table-row">
                <div class="cell-10">
                    <?php if(!$request->sid) :?>
                        <span class="fx-n1 px-3 py-2 rounded-pill bg-gold text-white">진행 중</span>
                    <?php else :?>
                        <span class="fx-n1 px-3 py-2 rounded-pill bg-gold text-white">완료</span>
                    <?php endif;?>
                </div>
                <div class="cell-30">
                    <p class="fx-n1"><?=nl2br(htmlentities($request->content))?></p>
                </div>
                <div class="cell-15">
                    <span class="fx-n1"><?=$request->user_name?></span>
                    <span class="fx-n2 text-gold">(<?=$request->user_id?>)</span>
                </div>
                <span class="cell-15">
                    <span class="fx-n2"><?=date("Y년 m월 d일", strtotime($request->start_date))?></span>
                </span>
                <div class="cell-15">
                    <span class="fx-n1"><?=number_format($request->cnt)?></span>
                    <small class="text-muted fx-n2">개</small>
                </div>
                <div class="cell-15">
                    <?php if(!$request->sid && user()->auth && array_search($request->id, $myList) === false): ?>
                        <button class="black-btn fx-n2 px-2 py-2" data-toggle="modal" data-target="#response-modal" data-id="<?=$request->id?>">견적 보내기</button>
                    <?php elseif($request->uid == user()->id) :?>
                        <button class="black-btn fx-n2 px-2 py-2">견적 보기</button>
                    <?php else: ?>
                        -
                    <?php endif;?>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<!-- / 요청 영역 -->

<!-- 응답 영역 -->
<?php if(user()->auth):?>
<div class="bg-white">
    <div class="container padding pb-5">
        <div class="sticky-top bg-white pt-4">
            <div class="d-between pr-4">
                <div>
                    <span class="text-muted">보낸 견적 목록</span>
                    <div class="title mt-n1">RESPONSES</div>
                </div>
                <div>
                    <span class="mr-2">보낸 견적</span>
                    <span class="fx-2 text-gold">5</span>
                    <span class="mr-4 text-muted">개</span>
                    <span class="mr-2">총 금액</span>
                    <span class="fx-2 text-gold">500,000</span>
                    <span class="text-muted">원</span>
                </div>
            </div>
            <div class="table-head mt-2">
                <div class="cell-10">상태</div>
                <div class="cell-30">내용</div>
                <div class="cell-15">요청자</div>
                <div class="cell-15">입력한 비용</div>
                <div class="cell-15">시공일</div>
                <div class="cell-15">+</div>
            </div>
        </div>
        <div class="list">
            <?php foreach($responses as $res):?>
                <div class="table-row">
                    <div class="cell-10">
                        <?php if(!$res->sid):?>
                            <span class="fx-n1 px-3 py-2 rounded-pill bg-gold text-white">진행 중</span>
                        <?php elseif($res->sid == $res->id) :?>
                            <span class="fx-n1 px-3 py-2 rounded-pill bg-gold text-white">선택</span>
                        <?php else: ?>
                            <span class="fx-n1 px-3 py-2 rounded-pill bg-gold text-white">미선택</span>
                        <?php endif;?>
                    </div>
                    <div class="cell-30">
                        <p class="fx-n1"><?=nl2br(htmlentities($res->content))?></p>
                    </div>
                    <div class="cell-15">
                        <span class="fx-n1"><?=$res->user_name?></span>
                        <span class="fx-n2 text-gold">(<?=$res->user_id?>)</span>
                    </div>
                    <div class="cell-15">
                        <span class="fx-n1"><?=number_format($res->price)?></span>
                        <small class="text-muted fx-n2">원</small>
                    </div>
                    <span class="cell-15">
                        <span class="fx-n2"><?=date("Y년 m월 d일", strtotime($res->start_date))?></span>
                    </span>
                    <div class="cell-15">
                        -
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<?php endif;?>
<!-- / 응답 영역 -->


<!-- 요청 모달 -->
<div id="request-modal" class="modal fade">
    <div class="modal-dialog">
        <form action="/estimates/requests" method="post" class="modal-content">
            <div class="modal-body px-4 py-5">
                <div class="px-3 text-center mb-5">
                    <div class="title">REQUEST</div>
                </div>
                <div class="form-group">
                    <label for="start_date">시공일</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="content" cols="30" rows="10" placeholder="작업 내용을 상세하게 적어주세요!"></textarea>
                </div>
                <div class="form-group mt-4">
                    <button class="black-btn w-100 fx-n2 py-2">작성 완료</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /요청 모달 -->

<!-- 응답 모달 -->
<div id="response-modal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" action="/estimates/responses" class="modal-content">
            <input type="hidden" id="qid" name="qid">
            <div class="modal-body px-4 py-5">
                <div class="px-3 text-center mb-5">
                    <div class="title">RESPONSE</div>
                </div>
                <div class="form-group">
                    <label for="price">비용</label>
                    <input type="text" class="form-control" id="price" name="price" placeholder="비용을 입력하세요">
                </div>
                <div class="form-group mt-4">
                    <button class="black-btn w-100 fx-n2 py-2">작성 완료</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        $("[data-target='#response-modal']").on("click", function(){
            $("#qid").val(this.dataset.id);
        });
    });
</script>
<!-- /응답 모달 -->
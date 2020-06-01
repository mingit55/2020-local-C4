<!-- 비주얼 영역 -->
<div id="visual" class="sub">
    <div class="images">
        <img src="/images/slide/2.jpg" alt="슬라이드 이미지" title="슬라이드 이미지">
    </div>
    <div class="position-center text-center mt-4">
        <div class="fx-7 text-white font-weight-bolder segoe mt-n4">PARTY</div>
        <p class="text-gray fx-n3 mb-5 mb-lg-0">나만의 멋진 공간을 모두에게 알려보세요</p>
    </div>
</div>
<!-- /비주얼 영역 -->

<!-- 온라인 집들이 -->
<div class="container padding">
    <div class="d-between align-items-end">
        <div>
            <span class="text-muted">온라인 집들이</span>
            <div class="title mt-n1">KNOWHOWS</div>
        </div>
        <button class="border-btn" data-toggle="modal" data-target="#write-modal">
            글쓰기
            <i class="fa fa-pencil ml-2"></i>
        </button>
    </div>
    <hr class="mt-4 mb-5">
    <div class="row">
        <?php foreach($knowhows as $knowhow): ?>
            <div class="col-lg-4 col-md-6 mb-5 mb-lg-none">
                <div class="knowhow-item border h-100">
                    <div class="image" style="height: 250px">
                        <img src="/upload/knowhows/<?=$knowhow->before_img?>" alt="Before 이미지" title="Before 이미지">
                        <img src="/upload/knowhows/<?=$knowhow->after_img?>" alt="After 이미지" title="After 이미지">
                    </div>
                    <div class="px-3 py-3">
                        <div class="d-between">
                            <div>
                                <span><?=$knowhow->user_name?></span>
                                <small class="text-gold ml-1">(<?=$knowhow->user_id?>)</small>
                                <small class="text-muted ml-1"><?=date("Y년 m월 d일", strtotime($knowhow->created_at))?></small>
                            </div>
                            <div class="text-gold">
                                <i class="fa fa-star"></i>
                                <span class="text-darkgold"><?=$knowhow->cnt == 0 ? 0 : floor($knowhow->total / $knowhow->cnt)?></span>
                            </div>
                        </div>
                        <div class="mt-2 text-muted fx-n1">
                            <?=nl2br(htmlentities($knowhow->content))?>
                        </div>
                        <?php if(array_search($knowhow->id, $myList) === false && $knowhow->uid !== user()->id):?>
                            <div class="mt-3 d-between">
                                <small class="text-muted">이 게시글의 평점은?</small>
                                <button class="black-btn fx-n2 py-1 px-3" data-target="#score-modal" data-toggle="modal" data-id="<?=$knowhow->id?>">평점 주기</button>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
<!-- /온라인 집들이 -->

<!-- 글쓰기 모달 -->
<div id="write-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/knowhows" method="post" class="modal-body py-5 px-4" enctype="multipart/form-data">
                <div class="px-3 text-center mb-5">
                    <div class="title">KNOWHOW</div>
                </div>
                <div class="form-group">
                    <label for="before_img">Before 사진</label>
                    <div class="custom-file">
                        <input type="file" id="before_img" name="before_img" class="custom-file-input">
                        <label for="before_img" class="custom-file-label">파일을 업로드 하세요</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="after_img">After 사진</label>
                    <div class="custom-file">
                        <input type="file" id="after_img" name="after_img" class="custom-file-input">
                        <label for="after_img" class="custom-file-label">파일을 업로드 하세요</label>
                    </div>
                </div>
                <div class="form-group">
                    <textarea  class="form-control" name="content" id="content" cols="30" rows="10" placeholder="자신만의 노하우를 모두에게 알리세요!"></textarea>
                </div>
                <div class="form-group mt-4">
                    <button class="black-btn w-100 fx-n2 py-2">작성 완료</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /글쓰기 모달 -->

<!-- 평점 모달 -->
<div id="score-modal" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content" action="/knowhows/reviews" method="post">
            <input type="hidden" id="score" name="score">
            <input type="hidden" id="kid" name="kid">
            <div class="modal-body px-3 py-3 text-center">
                <small>이 글의 평점을 매겨주세요!</small>
                <div class="mt-2">
                    <button class="text-gold border-gold px-3 border py-1">
                        <i class="fa fa-star"></i>
                        1
                    </button>
                    <button class="text-gold border-gold px-3 border py-1">
                        <i class="fa fa-star"></i>
                        2
                    </button>
                    <button class="text-gold border-gold px-3 border py-1">
                        <i class="fa fa-star"></i>
                        3
                    </button>
                    <button class="text-gold border-gold px-3 border py-1">
                        <i class="fa fa-star"></i>
                        4
                    </button>
                    <button class="text-gold border-gold px-3 border py-1">
                        <i class="fa fa-star"></i>
                        5
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        $("[data-target='#score-modal']").on("click", function(){
            $("#kid").val(this.dataset.id);
        });

        $("#score-modal form").on("submit", e => e.preventDefault());

        $("#score-modal button").on("click", e => {
            let score = parseInt(e.target.innerText);
            $("#score").val(score);
            $("#score-modal form")[0].submit();
        });
    });
</script>
<!-- /평점 모달 -->
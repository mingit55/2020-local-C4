<!-- 비주얼 영역 -->
<div id="visual" class="sub">
    <div class="images">
        <img src="/images/slide/3.jpg" alt="슬라이드 이미지" title="슬라이드 이미지">
    </div>
    <div class="position-center text-center mt-4">
        <div class="fx-7 text-white font-weight-bolder segoe mt-n4">SPECIALIST</div>
        <p class="text-gray fx-n3 mb-5 mb-lg-0">당신의 꿈을 현실로 만들어줄 전문가들이 한 곳에 모였습니다</p>
    </div>
</div>
<!-- /비주얼 영역 -->

<!-- 전문가 소개 -->
<div class="container padding pb-5">
    <div>
        <span class="text-muted">전문가 소개</span>
        <div class="title mt-n1">SPECIALISTS</div>
    </div>
    <hr class="mt-4 mb-5">
    <div class="row">
        <?php foreach($userList as $user):?>
            <div class="special-item col-lg-3 col-6 mb-4 mb-lg-0">
                <div class="card" style="transform: none;">
                    <div class="front">
                        <img src="/upload/users/<?=$user->photo?>" alt="전문가 이미지" title="전문가 이미지">
                    </div>
                    <div class="back">
                        <div class="text-center d-flex flex-column align-items-center mb-4">
                            <div class="fx-2"><?=$user->user_name?></div>
                            <div class="fx-n3 text-muted">(<?=$user->user_id?>)</div>
                            <div class="mt-2">
                                <?php for($i = 0; $i < ($user->cnt == 0 ? 0 : floor($user->total / $user->cnt)); $i ++ ):?>
                                    <i class="fa fa-star"></i>
                                <?php endfor;?>
                                <?php for(; $i < 5; $i ++): ?>
                                    <i class="fa fa-star-o"></i>
                                <?php endfor;?>
                            </div>
                            <div class="bar my-3"></div>
                            <button class="black-btn fx-n3 py-2" data-toggle="modal" data-target="#review-modal" data-id="<?=$user->id?>">시공 후기작성</button>
                        </div>                         
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
<!-- /전문가 소개 -->

<!-- 리뷰 목록 -->
<div class="bg-gray">
    <div class="container padding pt-5">
        <div class="bg-gray sticky-top pt-5">
            <div>
                <span class="text-muted">전문가 시공 후기</span>
                <div class="title mt-n1">REVIEWS</div>
            </div>
            <div class="table-head mt-3">
                <div class="cell-15">
                    전문가 정보
                </div>  
                <div class="cell-40">
                    내용
                </div>
                <div class="cell-15">
                    작성자
                </div>
                <div class="cell-15">
                    비용
                </div>
                <div class="cell-15">
                    평점
                </div>
            </div>
        </div>
        <div id="view-list" class="list overflow-hidden">

        </div>
    </div>
</div>
<!-- /리뷰 목록 -->

<!-- 후기 작성 폼 -->
<div id="review-modal" class="modal fade">
    <div class="modal-dialog">
        <form action="/specialists/reviews" class="modal-content" method="post">
            <input type="hidden" id="sid" name="sid">
            <div class="modal-body">
                <div class="px-3 text-center mb-5">
                    <div class="title">REVIEW</div>
                </div>
                <div class="form-group">
                    <label for="price">비용</label>
                    <input type="text" id="price" name="price" class="form-control" placeholder="금액을 입력해 주세요">
                </div>
                <div class="form-group">
                    <label for="score">평점</label>
                    <select name="score" id="score" class="form-control">
                        <option value="1">1점</option>
                        <option value="2">2점</option>
                        <option value="3">3점</option>
                        <option value="4">4점</option>
                        <option value="5">5점</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="content" cols="30" rows="10" class="form-control" placeholder="작업에 대한 구체적인 후기를 작성해 주세요!"></textarea>
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
        $("[data-target='#review-modal']").on("click", function(){
            $("#sid").val(this.dataset.id);
        });

        let list = [];
        
        $(window).on("scroll", e => {
            let scrollTop = $("html").scrollTop();
            let doc_h = $("html").height();
            let win_h = window.innerHeight;

            if(scrollTop + win_h == doc_h){
                let viewList = list.splice(0, 5).map(item => {
                    let html = `<div class="table-row position-relative" style="opacity: 0; left: 100%">
                        <div class="cell-2150">
                            <span class="fx-1">${item.s_name}</span>
                            <span class="fx-n2 text-gold">(${item.s_id})</span>
                        </div>
                        <div class="cell-40 px-3">
                            <p>${item.content}</p>
                        </div>
                        <div class="cell-15">
                            <span class="fx-1">${item.user_name}</span>
                            <span class="fx-n2 text-gold">(${item.user_id})</span>
                        </div>
                        <div class="cell-15">
                            <span>${parseInt(item.price).toLocaleString()}</span>
                            <small class="text-muted">원</small>
                        </div>
                        <div class="cell-15">
                            <div class="text-gold">`;
                    
                    for(var i = 0; i < item.score; i ++)
                        html += `<i class="fa fa-star"></i>`;
                                
                    for(; i < 5; i ++)
                        html += `<i class="fa fa-star-o"></i>`;

                    html +=  `</div>
                        </div>
                    </div>`;
                    return $(html);
                });

                viewList.forEach((x, i) => {
                    $("#view-list").append(x);
                    setTimeout(() => {
                        $(x).animate({
                            left: "0%" ,
                            opacity: "1"
                        }, 1000);
                    }, i * 100);
                });
            }
        });

        $.getJSON("/specialists/reviews", function(res){
            if(res.result == false)  return;
            list = res.list;
        });
    });
</script>
<!-- /후기 작성 폼 -->
class App {
    cartList = [];
    $cartList = $("#cart-list");
    $storeList = $("#store-list");
    $dropArea = $("#drop-area");

    constructor(){
        this.init();
    }

    async init(){
        this.products = await this.getProducts();

        this.storeUpdate()
        this.cartUpdate();
        this.setEvent();
    }

    storeUpdate(){
        let viewList = this.products;
        viewList.forEach(item => item.init())

        viewList = viewList.sort((a, b) => a.id - b.id);

        if(this.keyword){
            let regex = new RegExp(this.keyword, "g");
            viewList = viewList.filter(product => regex.test(product.product_name) || regex.test(product.brand))
                .map(product => {
                    product.product_name = product.product_name.replace(regex, m1 => `<mark>${m1}</mark>`);
                    product.brand = product.brand.replace(regex, m1 => `<mark>${m1}</mark>`);
                    return product;
                });
        }

        this.$storeList.html("");
        if(viewList.length > 0){
            viewList.forEach(item => {
                item.storeUpdate();
                this.$storeList.append(item.$storeElem);
            });
        } else {
            this.$storeList.html(`<div class="col-12 py-5 text-center text-muted">일치하는 상품이 없습니다.</div>`);
        }
    }

    cartUpdate(){
        this.$cartList.html("");

        let total = this.cartList.reduce((p, c) => p + c.buyCount * c.price, 0);

        if(this.cartList.length > 0) {
            this.cartList.forEach(item => {
                item.cartUpdate();
                this.$cartList.append(item.$cartElem);
            });
        } else {
            this.$cartList.html("<div class='w-100 py-5 text-center text-muted'>장바구니에 담긴 상품이 없습니다.<div>");
        }

        $(".total-price").text(total.toLocaleString());
    }

    getProducts(){
        return fetch("/json/store.json")
            .then(res => res.json())
            .then(jsonList => jsonList.map(jsonItem => new Product(this, jsonItem)))
    }

    setEvent(){
        // 장바구니 담기
        let target;
        let startPoint;
        this.$storeList.on("dragstart", ".image", (e) => {
            e.preventDefault();
            
            target = e.currentTarget;
            startPoint = [e.pageX, e.pageY];

            target.style.transition = "none";
        });

        $(window).on("mousemove", e => {
            if(!target || !startPoint || e.which !== 1) return;

            let x = e.pageX - startPoint[0];
            let y = e.pageY - startPoint[1];
            
            $(target).css({
                left: x + "px",
                top: y + "px",
                zIndex: 2000
            })
        });

        let timeout;
        $(window).on("mouseup", e => {
            if(!target || !startPoint || e.which !== 1) return;


            let width = this.$dropArea.width();
            let height = this.$dropArea.height();
            let {left, top} = this.$dropArea.offset();


            if(left <= e.pageX && e.pageX <= left + width && top <= e.pageY && e.pageY <= top + height){
                // # 장바구니에 담겨졌다면
                if(timeout) {
                    clearTimeout(timeout);
                }

                let temp = target;
                $(temp).css({
                    left: 0,
                    top: 0,
                    zIndex: 0,
                });

                this.$dropArea.removeClass("success");
                this.$dropArea.removeClass("error");

                
                let id = target.dataset.id;
                let idx = this.products.findIndex(item => item.id == id);
                let product = this.products[idx];

                if(this.cartList.some(item => item == product)){
                    this.$dropArea.addClass("error");
                } else {
                    product.buyCount = 1;
                    this.$dropArea.addClass("success");

                    this.products.splice(idx, 1); // 목록에서 삭제
                    this.cartList.push(product);  // 장바구니 추가
                    this.cartUpdate();
                    this.storeUpdate();
                }


                timeout = setTimeout(() => {
                    this.$dropArea.removeClass("success");
                    this.$dropArea.removeClass("error");
                }, 1500);

                
            } else {
                // # 장바구니에 담겨지지 않았다면
                $(target).animate({
                    left: 0,
                    top: 0
                }, 350, function(){
                    this.style.zIndex = 0;
                });
            }

            target = null;
            startPoint = null;
        });


        // 구매 수량
        this.$cartList.on("input", ".buy-count", e => {
            let id = e.target.dataset.id;
            let product = this.products.find(item => item.id == id);
            
            let value = parseInt(e.currentTarget.value);
            if(isNaN(value) || !value || value < 1) {
                value = 1;
            }
            product.buyCount = value;
            this.cartUpdate();

            e.target.focus();
        });

        // 삭제
        this.$cartList.on("click", ".remove-btn", e => {
            let id = e.target.dataset.id;
            let idx = this.cartList.findIndex(item => item.id == id);

            if(idx >= 0){
                let product = this.cartList[idx];
                product.buyCount = 0;

                this.cartList.splice(idx, 1); // 장바구니에서 삭제
                this.cartUpdate();

                this.products.push(product) // 목록에 다시 추가
                this.storeUpdate();
            }
        });

        // 구매하기
        $("#buy-modal form").on("submit", e =>{ 
            e.preventDefault();

            const PADDING = 30;
            const TEXT_SIZE = 15;
            const TEXT_GAP = 10;

            let canvas = document.createElement("canvas");
            let ctx = canvas.getContext('2d');
            ctx.font = `${TEXT_SIZE}px 나눔스퀘어, sans-serif`;

            let now = new Date();
            let totalPrice = this.cartList.reduce((p, c) => p + c.buyCount * c.price, 0);

            let str_time = `구매 일시         ${now.getFullYear()}-${now.getMonth()+1}-${now.getDate()} ${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;
            let str_price = `총 합계         ${totalPrice.toLocaleString()}원`;

            let viewList = [
                ...this.cartList.map(item => {
                    let text = `${item.json.product_name}         ${item.price.toLocaleString()}원 × ${item.buyCount.toLocaleString()}개 = ${(item.buyCount * item.price).toLocaleString()}원`;
                    let width = ctx.measureText(text).width;
                    return {text, width};
                }),
                { text: str_time, width: ctx.measureText(str_time).width },
                { text: str_price, width: ctx.measureText(str_price).width },
            ];

            let maxw = viewList.reduce((p, c) => Math.max(p, c.width), 0);
            canvas.width = maxw + PADDING;
            canvas.height = (TEXT_SIZE + TEXT_GAP) * viewList.length + PADDING * 2;

            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.font = `${TEXT_SIZE}px 나눔스퀘어, sans-serif`;
            viewList.forEach((x, i) => {
                let y = PADDING + (TEXT_SIZE + TEXT_GAP) * i;
                ctx.fillText(x.text, PADDING, y);
            });
            
            let url = canvas.toDataURL();

            this.products = this.products.concat(this.cartList)
            this.cartList = [];
            this.cartUpdate();
            this.storeUpdate();

            $("#view-modal img").attr("src", url);
            $("#view-modal").modal('show');
            $("#buy-modal").modal("hide");
        });


        // 검색
        let overview = [];
        $(".search input").on("input", e => {
            this.keyword = e.target.value
                .replace(/([\^$\.+*\[\]\(\)\\\\\\/?])/g, "\\$1")
                .replace(/(ㄱ)/g, "[가-깋]")
                .replace(/(ㄴ)/g, "[나-닣]")
                .replace(/(ㄷ)/g, "[다-딯]")
                .replace(/(ㄹ)/g, "[라-맇]")
                .replace(/(ㅁ)/g, "[마-밓]")
                .replace(/(ㅂ)/g, "[바-빟]")
                .replace(/(ㅅ)/g, "[사-싷]")
                .replace(/(ㅇ)/g, "[아-잏]")
                .replace(/(ㅈ)/g, "[자-짛]")
                .replace(/(ㅊ)/g, "[차-칳]")
                .replace(/(ㅋ)/g, "[카-킿]")
                .replace(/(ㅌ)/g, "[타-팋]")
                .replace(/(ㅍ)/g, "[파-핗]")
                .replace(/(ㅎ)/g, "[하-힣]");
            
            this.storeUpdate();

            $(".search .plus").html("");

            if(this.keyword.length === 0) return;

            let regex = new RegExp(this.keyword, "g");

            overview = [];
            this.products.forEach(item => {
                let {brand, product_name} = item.json;
                if(!overview.includes(brand) && regex.test(brand)){
                    overview.push(brand);
                }
                if(!overview.includes(product_name) && regex.test(product_name)){
                    overview.push(product_name);
                }
            });
            
            overview.forEach(item => {
                $(".search .plus").append(`<div class="item">${item}</div>`);
            });
            
        });

        // 자동완성 이동
        let sid = null;
        $(".search input").on("keydown", e => {
            if(e.keyCode !== 38 && e.keyCode !== 40){
                return;
            }

            // up 38, down 40
            $(".search .plus .item").removeClass("active");

            if(sid == null){
                sid = 1;
            } else {
                if(e.keyCode == 40){
                    sid = sid + 1 <= overview.length ? sid + 1 : overview.length;
                } else if(e.keyCode == 38) {
                    sid = sid - 1 >= 1 ? sid - 1 : 1;
                }
            }
            
            let selected = $(`.search .plus .item:nth-child(${sid})`);
            selected.addClass("active");

            let text = selected.text();
            $(".search input").val(text);

            this.keyword = text;
            this.storeUpdate();
        });
    }
}

window.onload = function(){
    window.app = new App();
};
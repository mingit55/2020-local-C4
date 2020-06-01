class Product {
    buyCount = 0;

    constructor(app, json){
        json.price = parseInt(json.price.replace(/[^0-9]/, ""));
        this.json = json;

        this.init();
    }

    init(){
        const {id, product_name, brand, price, photo} = this.json;
        this.id = id;
        this.product_name = product_name;
        this.brand = brand;
        this.price = price;
        this.photo = photo;
    }

    cartUpdate(){
        const {product_name, brand,} = this.json;
        const {id, price, photo} = this;
        
        let total = price * this.buyCount;

        if(!this.$cartElem) {
            this.$cartElem = $(`
                <div class="table-row">
                    <div class="cell-50">
                        <div class="text-left px-3 d-flex align-items-center">
                            <img src="./images/products/${photo}" alt="상품 이미지" class="table-image">
                            <div class="table-data pl-4">
                                <div class="fx-n2 text-muted text-ellipsis" title="${brand}">${brand}</div>
                                <div class="fx-2 text-ellipsis" title="${product_name}">${product_name}</div>
                            </div>
                        </div>
                    </div>
                    <div class="cell-15">
                        <span class="fx-1">${price.toLocaleString()}</span>
                        <span class="fx-n2 text-muted">원</span>
                    </div>
                    <div class="cell-10">
                        <input type="number" min="1" value="${this.buyCount}" class="buy-count" data-id="${id}">
                    </div>
                    <div class="cell-15">
                        <span class="fx-1 text-gold total">${total.toLocaleString()}</span>
                        <span class="fx-n2 text-muted">원</span>
                    </div>
                    <div class="cell-10"> 
                        <button class="remove-btn" data-id="${id}">
                            &times;
                        </button>
                    </div>
                </div>
            `);
        } else {
            this.$cartElem.find(".buy-count").val(this.buyCount);
            this.$cartElem.find(".total").text(total.toLocaleString());
        }
    }

    storeUpdate(){
        const {id, product_name, brand, price, photo} = this;
        
        if(!this.$storeElem){
            this.$storeElem = $(`
                <div class="col-lg-4 col-6 mb-5">
                    <div class="store-item">
                        <div class="image" draggable="draggable=" data-id="${id}">
                            <img src="./images/products/${photo}" alt="상품 이미지" class="fit-cover">
                        </div>
                        <div class="mt-3 d-between align-items-end px-3">
                            <div class="w-50">
                                <div class="fx-n1 text-muted text-ellipsis brand">${brand}</div>
                                <div class="fx-2 text-gold text-ellipsis  product_name">${product_name}</div>
                            </div>
                            <div class="w-50 text-right">
                                <div class="text-ellipsis">
                                    <span class="fx-4 text-gold">${price.toLocaleString()}</span>
                                    <small class="text-muted">원</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        } else {
            this.$storeElem.find(".brand").html(brand);
            this.$storeElem.find(".product_name").html(product_name);
        }
    }    
}
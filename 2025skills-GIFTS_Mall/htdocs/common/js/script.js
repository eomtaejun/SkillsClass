class Util{
    static post(method, url, data=null){
        return new Promise((res, rej)=>{
            let req=new XMLHttpRequest();
            req.open(method, url);
            req.addEventListener("readystatechange", e=>{
                if(req.readyState===req.DONE){
                    let json=JSON.parse(req.responseText);
                    if(req.status===200) res(json);
                    else rej(json);
                }
            })

            if(data){
                let form=new FormData();
                Object.keys(data).forEach(key=>{
                    form.append(key, data[key]);
                })
                req.send(form);
            } else req.send();
        })
    }
}

class Orders{
    constructor(){
        this.products=[];
        this.orders=[];
        this.categories=[];
        
        this.dragItem=null;
        this.dropItem=null;

        this.userid=null;
        this.allprice=0;

        this.init();
    }

    async init(){
        this.products=await Util.post("get", "/common/json/products.json");
        this.categories=[...new Set(this.products.map(value=>value.category))];

        this.userid=this.getId();
        
        this.load();
        this.event();
    }

    load(){
        $("#orderModal .userid").text(`비회원 ID: ${this.userid}`);
        $("#orderModal .products").html([
            ...this.categories.map(category=>`
                <div class="col-12 mb-3">
                    <p class="mb-0 fx-2 font-bold text-dark">${category}</p>
                </div>

                ${
                    this.products.filter(value=>value.category===category).map(item=>`
                        <div class="col-6 mb-4">
                            <div class="item" draggable="true" data-id="${item.id}">
                                <img src="/common/products/${item.category}/${item.img}" alt="product image" title="product image" class="fit-cover rounded-3 mb-2" draggable="false">
                                <p class="mb-1 font-bold text-black fx-1 word-wrap">${item.title}</p>
                                <p class="mb-0 fx-n1 text-secondary text-cancel word-wrap ${item.discount ? "" : "d-none"}">${item.price.toLocaleString()}원</p>
                                <p class="mb-0 fx-1 font-bold text-red word-wrap">${this.calcprice(item.id).toLocaleString()}원</p>
                            </div>
                        </div>
                    `).join("")
                }
            `)
        ])
    }

    event(){
        // 드래그 시작 (상품 추가)
        $("#orderModal .products").on("dragstart", e=>this.dragItem=e.target);

        // 기본 동작 방지
        document.querySelector("#orderModal .orders").addEventListener("dragover", e=>e.preventDefault());

        // 상품 추가
        document.querySelector("#orderModal .orders").addEventListener("drop", e=>{
            e.preventDefault();

            if(!this.dragItem) return;

            const products=this.products.find(value=>value.id===Number(this.dragItem.dataset.id));
            
            this.orders.push({
                ...products,
                quantity: 1
            });

            $(this.dragItem).addClass("ordered");
            this.dragItem=null;

            this.setOrders();
            this.setAllprice();
        })

        // 드래그 시작 (상품 삭제)
        $("#orderModal .orders").on("dragstart", e=>this.dropItem=e.target);

        // 기본 동작 방지
        document.querySelector("body").addEventListener("dragover", e=>e.preventDefault());

        // 상품 삭제
        document.querySelector("body").addEventListener("drop", e=>{
            e.preventDefault();

            if(e.target.closest(".orders")) return;
            if(!this.dropItem) return;
            if(!this.dropItem.classList.contains("copy")) return;

            this.orders=this.orders.filter(value=>value.id!==Number(this.dropItem.dataset.id));

            $(`#orderModal .products .item[data-id="${this.dropItem.dataset.id}"]`).removeClass("ordered");
            this.dropItem=null;

            this.setOrders();
            this.setAllprice();
        })

        // 수량 조절
        $("#orderModal .orders").on("input", "input[name='quantity']", e=>{
            this.orders.find(value=>value.id===Number(e.target.dataset.id)).quantity=Number(e.target.value);

            $(`#orderModal .orders .item .total-price-${e.target.dataset.id}`).text(`총 ${(this.calcprice(e.target.dataset.id)*Number(e.target.value)).toLocaleString()}원`);
            this.setAllprice();
        })
        $("#orderModal .orders").on("change", "input[name='quantity']", e=>{
            if(e.target.value>0) return;
            alert("잘못된 수량입니다");
            e.target.value=1;
            this.orders.find(value=>value.id===Number(e.target.dataset.id)).quantity=1;

            $(`#orderModal .orders .item .total-price-${e.target.dataset.id}`).text(`총 ${(this.calcprice(e.target.dataset.id)*Number(e.target.value)).toLocaleString()}원`);
            this.setAllprice();
        })

        // 주문
        $("#orderModal #order").on("click", e=>{
            $("#alert p").text(`방금 비회원 ${this.userid}님이 ${this.allprice.toLocaleString()}원을 결제하셨습니다!`);

            $("#alert").modal({backdrop: false});
            $("#alert").modal("show");

            setTimeout(() => {
                $("#alert").modal("hide"); // 모달 닫기
                this.orders=[]; // 주문 상품 초기화
                this.setOrders(); // 주문 영역 초기화
                this.setAllprice(); // 전체 가격 초기화
                document.querySelectorAll("#orderModal .products .item.ordered").forEach(element=>$(element).removeClass("ordered")); // 전시 영역 초기화
                this.userid=this.getId(); // 비회원 아이디 초기화
                $("#orderModal .userid").text(`비회원 ID: ${this.userid}`); // 비회원 아이디 초기화
            }, 3000);
        })
    }

    setOrders(){
        $("#orderModal .orders").html([
            ...this.orders.map(item=>`
                <div class="col-6 mb-4">
                    <div class="item copy" draggable="true" data-id="${item.id}">
                        <img src="/common/products/${item.category}/${item.img}" alt="product image" title="product image" class="fit-cover rounded-3 mb-2" draggable="false">
                        <p class="mb-1 font-bold text-black fx-1 word-wrap">${item.title}</p>
                        <p class="mb-0 fx-1 font-bold text-dark word-wrap">${this.calcprice(item.id).toLocaleString()}원</p>
                        <div class="d-flex justify-content-start align-items-center col-gap-2 my-1">
                            <input type="number" name="quantity" class="form-control" value="${item.quantity}" min="1" data-id="${item.id}">
                            <p class="mb-0 text-black">개</p>
                        </div>
                        <p class="mb-0 fx-2 font-bold text-red word-wrap text-end total-price-${item.id}">총 ${(this.calcprice(item.id)*item.quantity).toLocaleString()}원</p>
                    </div>
                </div>
            `)
        ])
    }

    setAllprice(){
        let result=0;
        this.orders.forEach(value=>{
            result+=this.calcprice(value.id)*value.quantity;
        })
        $("#orderModal .all-price").text(`총 ${result.toLocaleString()}원`);
        this.allprice=result;

        if(this.orders.length<1) $("#orderModal #order").addClass("disabled");
        else $("#orderModal #order").removeClass("disabled");
    }

    calcprice(id){
        const product=this.products.find(value=>value.id===Number(id));

        let result=0;
        switch(product.discount){
            case "10000":
                result=product.price-10000;
                break;
            case "10%":
                result=Math.round(product.price*0.9);
                break;
            case "30%":
                result=Math.round(product.price*0.7);
                break;
            default:
                result=product.price;
                break;
        }

        return result;
    }

    getId(){
        let rand=crypto.randomUUID().slice(0, 6);

        let result=[];
        for(let i=0; i<6; i++){
            if(Math.random()>0.5) result.push(rand[i].toUpperCase());
            else result.push(rand[i].toLowerCase());
        }

        if(/^(?=.*[\d])(?=.*[a-zA-Z]).+$/.test(result.join(""))) return result.join("");
        else return this.getId();
    }
}

class Motto{
    constructor(){
        this.container=document.querySelector(".motto-container");
        this.idx=[1, 2, 3, 4, 5];

        this.event();
    }
    
    event(){
        document.addEventListener("mouseover", e=>{
            this.idx.forEach(value=>{
                $(this.container).removeClass(`id-${value}`);
            })

            if(!e.target.dataset.id) return;

            $(this.container).addClass(`id-${e.target.dataset.id}`);
        })
    }
}

class Video{
    constructor(){
        this.video=document.querySelector("video");
        this.video.muted=true;
        
        this.options={
            playstate: false,
            loop: false,
            autoplay: JSON.parse(localStorage.getItem("video_autoplay")),
            hide: false
        };

        this.load(true);
        this.event();
    }

    event(){
        $(".video-container .controls, .video-container .controls-sub").on("click", e=>this.control(e));

        this.video.addEventListener("ended", e=>{
            if(!this.options.loop){
                this.options.playstate=false;
                this.load();
            }
        })
    }

    load(init=false){
        // 자동재생
        if(init && this.options.autoplay){
            this.video.play();
            this.options.playstate=true;
        }

        // 재생/일시정지
        document.querySelector(".video-container .controls .fa.fa-play").parentElement.classList.toggle("disabled", this.options.playstate);
        document.querySelector(".video-container .controls .fa.fa-pause").parentElement.classList.toggle("disabled", !this.options.playstate);

        // 컨트롤러 보이기/숨기기
        document.querySelector(".video-container .controls").classList.toggle("hide", this.options.hide);
        document.querySelector(".video-container .controls-sub").classList.toggle("hide", !this.options.hide);
        
        // 반복재생
        document.querySelector(".video-container .controls .fa.fa-repeat").parentElement.classList.toggle("off", !this.options.loop);
        if(this.options.loop) document.querySelector(".video-container .controls .fa.fa-repeat").parentElement.setAttribute("title", "반복재생 켜짐");
        else document.querySelector(".video-container .controls .fa.fa-repeat").parentElement.setAttribute("title", "반복재생 꺼짐");
        
        // 자동재생
        document.querySelector(".video-container .controls .fa.fa-retweet").parentElement.classList.toggle("off", !this.options.autoplay);
        if(this.options.loop) document.querySelector(".video-container .controls .fa.fa-retweet").parentElement.setAttribute("title", "자동재생 켜짐");
        else document.querySelector(".video-container .controls .fa.fa-retweet").parentElement.setAttribute("title", "자동재생 꺼짐");
    }

    control(e){
        const element=e.target;
        const control=element.classList.contains("fa") ? element : element.children[0];
        const video=this.video;

        switch(control.classList[1]){
            case "fa-play":
                video.play();
                this.options.playstate=true;
                this.load();
                break;
            case "fa-pause":
                video.pause();
                this.options.playstate=false;
                this.load();
                break;
            case "fa-square":
                video.pause();
                video.currentTime=0;
                this.options.playstate=false;
                this.load();
                break;
            case "fa-backward":
                video.currentTime-=10;
                break;
            case "fa-forward":
                video.currentTime+=10;
                break;
            case "fa-minus":
                if(video.playbackRate<0.6) return;
                video.playbackRate-=0.1;
                $(".controls .playbackrate").text(`${Math.floor(video.playbackRate*10)/10}x`)
                break;
            case "fa-plus":
                if(video.playbackRate>3) return;
                video.playbackRate+=0.1;
                $(".controls .playbackrate").text(`${Math.floor(video.playbackRate*10)/10}x`)
                break;
            case "fa-refresh":
                video.playbackRate=1;
                $(".controls .playbackrate").text(`${Math.floor(video.playbackRate*10)/10}x`)
                break;
            case "fa-eye-slash":
                this.options.hide=true;
                this.load();
                break;
            case "fa-eye":
                this.options.hide=false;
                this.load();
                break;
            case "fa-repeat":
                this.options.loop=!this.options.loop;
                video.loop=this.options.loop;
                this.load();
                break;
            case "fa-retweet":
                this.options.autoplay=!this.options.autoplay;
                localStorage.setItem("video_autoplay", this.options.autoplay);
                this.load();
                break;
        }
    }
}

class Users{
    constructor(){
        // 회원가입
        this.join_form=document.querySelector("#join_form");
        this.join_userid=document.querySelector("#join_userid");
        this.join_userpass=document.querySelector("#join_userpass");
        this.join_username=document.querySelector("#join_username");
        this.join_email=document.querySelector("#join_email");
        
        // 로그인
        this.login_form=document.querySelector("#login_form");
        this.login_userid=document.querySelector("#login_userid");
        this.login_userpass=document.querySelector("#login_userpass");

        this.event();
    }

    event(){
        // 회원가입
        this.join_form.addEventListener("submit", async e=>{
            e.preventDefault();

            if(this.join_userid.value.trim().length<1 || this.join_userpass.value.trim().length<1 || this.join_username.value.trim().length<1 || this.join_email.value.trim().length<1) return alert("필수 항목 누락");

            try{
                let data=await Util.post("post", "/users/join_action", {
                    userid: this.join_userid.value,
                    userpass: this.join_userpass.value,
                    username: this.join_username.value,
                    email: this.join_email.value
                })

                switch(data.msg){
                    case "exists":
                        alert("중복된 ID입니다");
                        break;
                    case "success":
                        alert("회원가입 완료");
                        location.reload();
                        break;
                    default:
                        console.log("error");
                        break;
                }
            } catch(err){
                console.log(err);
            }
        })

        // 로그인
        this.login_form.addEventListener("submit", async e=>{
            e.preventDefault();

            if(this.login_userid.value.trim().length<1 || this.login_userpass.value.trim().length<1) return alert("필수 항목 누락");

            try{
                let data=await Util.post("post", "/users/login_action", {
                    userid: this.login_userid.value,
                    userpass: this.login_userpass.value
                })

                switch(data.msg){
                    case "undefined":
                        alert("일치하는 ID가 없습니다");
                        break;
                    case "mismatch":
                        alert("비밀번호가 일치하지 않습니다");
                        break;
                    case "success":
                        alert("로그인 완료");
                        location.reload();
                        break;
                    default:
                        console.log("error");
                        break;
                }
            } catch(err){
                console.log(err);
            }
        })
    }
}

class Notifications{
    constructor(){
        this.notifications=[];
        this.selecting="all";

        this.totalcount=0;
        this.nowcount=0;

        this.init();
    }

    async init(){
        this.notifications=await Util.post("get", "/js/notification");
        this.notifications=this.notifications.sort((a, b)=>new Date(b.date).getTime() - new Date(a.date).getTime());

        this.totalcount=Math.ceil(this.notifications.filter(value=>value.type===this.selecting || this.selecting==="all").length/6);
        
        this.load();
        this.event();
    }

    load(){
        this.totalcount=Math.ceil(this.notifications.filter(value=>value.type===this.selecting || this.selecting==="all").length/6);
        $(".notification .page-text").text(`${this.nowcount+1}/${this.totalcount}`);
        
        $(".notification table tbody").html([
            ...this.notifications.filter(value=>value.type===this.selecting || this.selecting==="all")
            .filter((value, index)=>this.nowcount*6<=index && index<(this.nowcount*6)+6)
            .map(item=>`
                <tr>
                    <td class="py-3 text-center word-wrap">${item.id}</td>
                    <td class="py-3 text-center word-wrap"><span class="bg-${item.type==="이벤트" ? "coral" : "secondary"} rounded-pill fx-n4 text-light px-2 py-1 nowrap">${item.type}</span></td>
                    <td class="py-3 word-wrap">${item.title}</td>
                    <td class="py-3 text-center word-wrap">${item.date}</td>
                </tr>
            `)
        ])
    }

    event(){
        // 페이지
        $(".notification .control").on("click", "button", e=>{
            if(!e.target.dataset.page) return;
            if(e.target.dataset.page==="down"){
                if(this.nowcount<1) this.nowcount=0;
                else this.nowcount--;
            } else if(e.target.dataset.page==="up"){
                if(this.nowcount<this.totalcount-1) this.nowcount++;
                else this.nowcount=this.totalcount-1;
            }

            this.load();
        })

        // 유형
        $(".notification .control").on("change", "select", e=>{
            this.selecting=e.target.value;
            this.nowcount=0;
            this.load();
        })

        // 정렬
        $(".notification .control").on("click", "button", e=>{
            if(!e.target.dataset.sort) return;

            if(e.target.dataset.sort==="Desc"){
                this.notifications=this.notifications.sort((a, b)=>new Date(a.date).getTime() - new Date(b.date).getTime());
                $(".notification .control button[data-sort='Asc']").removeClass("d-none");
                $(".notification .control button[data-sort='Desc']").addClass("d-none");
            } else if(e.target.dataset.sort==="Asc"){
                this.notifications=this.notifications.sort((a, b)=>new Date(b.date).getTime() - new Date(a.date).getTime());
                $(".notification .control button[data-sort='Asc']").addClass("d-none");
                $(".notification .control button[data-sort='Desc']").removeClass("d-none");
            }
            this.load();
        })
    }
}

class Cart{
    constructor(){
        this.products=[];
        this.container=document.querySelector(".sales");

        this.init();
    }

    async init(){
        this.products=await Util.post("get", "/js/cart");

        this.event();
        this.setAllprice();
    }

    event(){
        $(this.container).on("click", async e=>{
            if(!e.target.dataset.id) return;

            let data=await Util.post("get", `/products/add?id=${e.target.dataset.id}`);

            let move=false;
            switch(data.msg){
                case "not user":
                    alert("로그인 후 이용 가능합니다");
                    break;
                case "success":
                    move=confirm("장바구니에 추가되었습니다. 장바구니로 이동하시겠습니까?");
                    if(move) location.href="/shoppingcart";
                    else location.reload();
                    break;
            }
        })

        $(".cart").on("input", "input[name='quantity']", async e=>{
            const product=this.products.find(value=>value.id==e.target.dataset.id);
            $(`.cart .total-price-${e.target.dataset.id}`).text(`${(this.calcprice(product.id)*Number(e.target.value)).toLocaleString()}원`);

            let data=await Util.post("post", "/cart/edit", {
                id: Number(e.target.dataset.id),
                quantity: Number(e.target.value)
            })

            this.setAllprice();
        })

        $(".cart").on("change", "input[name='quantity']", async e=>{
            if(e.target.value>0) return;

            const product=this.products.find(value=>value.id==e.target.dataset.id);

            alert("잘못된 수량입니다");
            e.target.value=1;
            $(`.cart .total-price-${e.target.dataset.id}`).text(`${(this.calcprice(product.id)*Number(e.target.value)).toLocaleString()}원`);

            let data=await Util.post("post", "/cart/edit", {
                id: Number(e.target.dataset.id),
                quantity: Number(e.target.value)
            })

            this.setAllprice();
        })
    }

    calcprice(id){
        const product=this.products.find(value=>value.id===Number(id));

        let result=0;
        switch(product.discount){
            case "10000":
                result=product.price-10000;
                break;
            case "10%":
                result=Math.round(product.price*0.9);
                break;
            case "30%":
                result=Math.round(product.price*0.7);
                break;
            default:
                result=product.price;
                break;
        }

        return result;
    }

    setAllprice(){
        let result=0;

        this.products.forEach(value=>{
            let quantity=document.querySelector(`.cart input[data-id='${value.id}']`) ? Number(document.querySelector(`.cart input[data-id='${value.id}']`).value) : 0;
            result+=this.calcprice(value.id)*quantity;
        })

        $(".cart .all-price").text(`${result.toLocaleString()}원`);
    }
}

class Product{
    constructor(){
        this.container=document.querySelector(".wrap");

        this.products=[];
        this.categories=[];

        this.init();
    }

    async init(){
        this.products=await Util.post("get", "/js/products");
        this.categories=[...new Set(this.products.map(value=>value.category))];

        this.products=this.products.sort((a, b)=>new Date(b.date).getTime() - new Date(a.date).getTime());
        
        this.load();
        this.event();
    }

    load(){
        $(this.container).html([
            ...this.categories.map(category=>`
                <div class="py-5">
                    <div class="d-flex justify-content-start align-items-center col-gap-2 mb-3">
                        <p class="mb-0 text-dark font-bold fx-4">${category}</p>
                    </div>
                    <div class="row">
                    ${
                        this.products.filter(value=>value.category===category).map(item=>`
                            <div class="col-10 col-md-4 mb-5">
                                <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                    <div class="item ${item.discount ? "rank" : ""} mb-3">
                                        <div class="badges ${item.discount ? "d-flex" : "d-none"} justify-content-start align-items-center col-gap-2">
                                            <p class="mb-0 px-2 py-1 rounded-3 text-light bg-red">인기상품</p>
                                            <p class="mb-0 px-2 py-1 rounded-3 text-light bg-green">할인상품</p>
                                        </div>

                                        <img src="/common/products/${item.category}/${item.img}" alt="product image" title="product image" class="fit-cover rounded-3 mb-2">
                                        <p class="mb-1 fx-1 text-secondary word-wrap">${item.date}</p>
                                        <p class="mb-1 font-bold text-black fx-2 word-wrap">${item.title}</p>
                                        <p class="mb-1 text-dark fx-n1 word-wrap">${item.detail}</p>
                                        <p class="mb-0 fx-1 text-secondary text-cancel word-wrap ${item.discount ? "" : "d-none"}">${item.price}원</p>
                                        <p class="mb-0 fx-3 font-bold text-red word-wrap">${this.calcprice(item.id).toLocaleString()}원</p>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center col-gap-2 w-100">
                                        <a href="/admins/produdts/edit?id=${item.id}" name="cart" class="btn btn-outline-primary py-2 fx-n1 w-50 rounded-3" data-id="${item.id}">수정하기</a>
                                        <a href="/admins/products/delete_action?id=${item.id}" class="btn btn-outline-danger py-2 fx-n1 w-50 rounded-3">삭제하기</a>
                                    </div>
                                </div>
                            </div>
                        `).join("")
                    }
                    </div>
                </div>
            `)
        ])
    }

    event(){
        $(".sales").on("click", "button[name='sort']", e=>{
            if(!e.target.dataset.sort) return;

            if(e.target.dataset.sort==="Desc"){
                this.products=this.products.sort((a, b)=>new Date(a.date).getTime() - new Date(b.date).getTime());
                $(".sales button[data-sort='Desc']").addClass("d-none");
                $(".sales button[data-sort='Asc']").removeClass("d-none");
            } else if(e.target.dataset.sort==="Asc"){
                this.products=this.products.sort((a, b)=>new Date(b.date).getTime() - new Date(a.date).getTime());
                $(".sales button[data-sort='Desc']").removeClass("d-none");
                $(".sales button[data-sort='Asc']").addClass("d-none");
            }
            this.load();
        })
    }

    calcprice(id){
        const product=this.products.find(value=>value.id===Number(id));

        let result=0;
        switch(product.discount){
            case "10000":
                result=product.price-10000;
                break;
            case "10%":
                result=Math.round(product.price*0.9);
                break;
            case "30%":
                result=Math.round(product.price*0.7);
                break;
            default:
                result=product.price;
                break;
        }

        return result;
    }
}


class Test{
    constructor(){
        this.products=[];
        this.notifications=[];

        this.init();
    }

    async init(){
        this.products=await Util.post("get", "/common/json/products.json");
        this.notifications=await Util.post("get", "/common/json/notifications.json");

        this.products.forEach(async value=>{
            await Util.post("post", "/test/products", value);
        })

        this.notifications.forEach(async value=>{
            await Util.post("post", "/test/notifications", value);
        })
    }
}
<div class="container" style="min-height: calc(100vh - 120px);">
    <section>
        <p class="mb-5 fx-6 font-bold text-black text-center">판매상품 수정</p>
        <form action="/admins/products/edit_action?id=<?=$product->id?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="fx-3 font-bold text-black mb-1">상품명</label>
                <input type="text" name="title" id="title" class="form-control" value="<?=$product->title?>">
            </div>

            <div class="mb-3">
                <label for="detail" class="fx-3 font-bold text-black mb-1">상세설명</label>
                <textarea name="detail" id="detail" class="form-control" style="resize: none;"><?=$product->detail?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="fx-3 font-bold text-black mb-1">가격</label>
                <input type="number" name="price" id="price" class="form-control" value="<?=$product->price?>" min="1">
            </div>

            <div class="mb-3">
                <label for="discount" class="fx-3 font-bold text-black mb-1">인기상품</label>
                <select name="discount" id="discount" class="form-select">
                    <option value="">인기상품 지정 안함</option>
                    <option value="10000">10,000원 할인</option>
                    <option value="10%">10% 할인</option>
                    <option value="30%">30% 할인</option>
                </select>
            </div>

            <div class="mb-3">
                <?php $categories=["건강식품", "디지털", "팬시", "향수", "헤어케어"]?>
                <label for="category" class="fx-3 font-bold text-black mb-1">카테고리</label>
                <select name="category" id="category" class="form-select">
                    <?php foreach($categories as $category):?>
                        <option value="<?=$category?>" <?=$product->category===$category ? "selected" : ""?>><?=$category?></option>
                    <?php endforeach;?>
                </select>
            </div>

            <div class="mb-5">
                <label for="img" class="fx-3 font-bold text-black mb-1">상품사진</label>
                <input type="file" name="img" id="img" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-dark w-100 rounded-3">상품 수정</button>
        </form>
    </section>
</div>
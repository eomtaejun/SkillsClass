<div class="container" style="min-height: calc(100vh - 120px);">
    <section>
        <p class="mb-5 fx-6 font-bold text-black text-center">공지사항 추가</p>
        <form action="/admins/notifications/add_action" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="fx-3 font-bold text-black mb-1">제목</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>

            <div class="mb-5">
                <label for="type" class="fx-3 font-bold text-black mb-1">카테고리</label>
                <select name="type" id="type" class="form-select">
                    <option value="">공지사항 선택</option>
                    <option value="일반">일반</option>
                    <option value="이벤트">이벤트</option>
                </select>
            </div>

            <button type="submit" class="btn btn-dark w-100 rounded-3">공지사항 추가</button>
        </form>
    </section>
</div>
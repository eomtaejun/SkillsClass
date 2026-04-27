
<div class="container" style="min-height: calc(100vh - 120px);">
    <section>
        <p class="mb-5 fx-6 font-bold text-black text-center">공지사항관리</p>
        <a href="/admins/notifications/add" class="btn btn-dark mb-4">공지사항 추가</a>
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">유형</th>
                    <th class="text-center">제목</th>
                    <th class="text-center">공지일자</th>
                    <th class="text-center">수정</th>
                    <th class="text-center">삭제</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($notifications as $notice):?>
                    <tr>
                        <td class="py-3 text-center"><?=$notice->id?></td>
                        <td class="py-3 text-center"><?=$notice->type?></td>
                        <td class="py-3 text-center"><?=$notice->title?></td>
                        <td class="py-3 text-center"><?=$notice->date?></td>
                        <td class="py-3 text-center"><a href="/admins/notifications/edit?id=<?=$notice->id?>" class="btn btn-outline-primary">수정</a></td>
                        <td class="py-3 text-center"><a href="/admins/notifications/delete_action?id=<?=$notice->id?>" class="btn btn-outline-danger">삭제</a></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </section>
</div>
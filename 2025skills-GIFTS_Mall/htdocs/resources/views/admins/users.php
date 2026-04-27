
<div class="container" style="min-height: calc(100vh - 120px);">
    <section>
        <p class="mb-5 fx-6 font-bold text-black text-center">회원관리</p>
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">이름</th>
                    <th class="text-center">이메일</th>
                    <th class="text-center">가입날짜</th>
                    <th class="text-center">암호화 비밀번호</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($users as $user):?>
                    <tr>
                        <td class="py-3 text-center"><?=$user->id?></td>
                        <td class="py-3 text-center"><?=$user->name?></td>
                        <td class="py-3 text-center"><?=$user->email?></td>
                        <td class="py-3 text-center"><?=$user->date?></td>
                        <td class="py-3 text-center"><?=$user->password?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </section>
</div>
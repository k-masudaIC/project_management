document.addEventListener('DOMContentLoaded', function() {
    const userInput = document.getElementById('user_name_input');
    if (!userInput) return;
    const datalist = document.getElementById('user_name_list');
    userInput.addEventListener('input', function() {
        // 入力時のサジェストはdatalistで自動
    });
});

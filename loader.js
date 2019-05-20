(function () {
    const BYTES_PER_CHUNK = 1024 * 1024 * 1;
    let form;
    let inputs;
    let currentChunk = 1;
    let currentSize = 0;

    window.onload = function() {
        form = document.getElementsByTagName('form')[0];
        inputs = form.getElementsByTagName('input');

        form.onsubmit = function (e) {
            e.preventDefault();
            sendRequest(inputs[0].files[0]);
        };
        inputs[0].onchange = function () {
            if (currentChunk > 1)
                onRefresh()
        }
    };

    function sendRequest(file)
    {
        let blob = file;
        let total = getChunksCount(file);
        uploadFile(blob, currentChunk, currentSize, total);
    }

    function uploadFile(blob, current, start, total, callback)
    {
        let end = start + BYTES_PER_CHUNK;
        if (end > blob.size)
            end = blob.size;

        getChunk(blob, start, end, function(chunk)
        {
            let xhr = new XMLHttpRequest();
            xhr.addEventListener('load', function () {
                onUpdateProgress((currentChunk * 100 / total));
                currentChunk++;
                currentSize += BYTES_PER_CHUNK;
                if(currentChunk > total) {
                    alert('Файл загружен!');
                    onRefresh(true);
                    return;
                }
                uploadFile(blob, currentChunk, currentSize, total, callback);
            });
            xhr.addEventListener('error', onConnectionError);
            xhr.open("post", "upload.php", true);
            xhr.setRequestHeader("X-File-Name", blob.name);
            xhr.setRequestHeader("X-CURRENT", current);
            xhr.setRequestHeader("X-Total", total);
            xhr.send(chunk);
        });
    }

    function getChunksCount(blob)
    {
        return Math.ceil(blob.size / BYTES_PER_CHUNK);
    }

    function getChunk(blob, start, end, callback)
    {
        let chunk = blob.slice(start, end);
        callback(chunk)
    }

    function onUpdateProgress(value) {
        let progressBar = document.getElementsByClassName('progress-bar');
        if (progressBar.length) {
            progressBar[0].style.width = value + "%";
        }
    }

    function onConnectionError() {
        inputs[1].value = 'Продолжить';
        alert('Соединение прервано! Нажмите кнопу продолжить, когда соединение будет установлено!')
    }

    function onRefresh(full = false) {
        if (full) {
            form.reset();
        }
        inputs[1].value = 'Отправить';
        currentChunk = 1;
        currentSize = 0;
        onUpdateProgress(0)
    }
})();
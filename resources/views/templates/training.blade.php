@extends('templates.layout')
<h1>WORDS</h1>
<hr>
@section('content')
<div style="font-size:40px;background-color: #ffe6e6;">

    <table border="1">
        <caption>Words</caption>
        <tr>
            <th >&#8470;</th>
            <th >Word</th>
{{--            <th >hint</th>--}}
            <th >Translate</th>
            <th >Buttons</th>
        </tr>
        @foreach($words as $word)
        <tr>
            <td>
                {{ $loop->index + 1 }}
            </td>

                <td class="@if( $word->known > 1 ) translate @endif">

                    <div class="content">
                    @foreach($word->englishWords as $w)

                        <span class="little-word @if($loop->index<3) primary @else secondary @endif @if($w->level > 4) rare @endif">
                            <span class="left-arrow" onclick="changePriority(event,this,1,'{{ route('changePriority', ['ru' => $word->id, 'en' => $w->id, 'num' => 1]) }}');">&#10094;</span>
                            <span class="middle">
                                <span class="up-button"><span class="cross" onclick="detach(event,'{{ route('detach', ['ru' => $word->id, 'en' => $w->id]) }}')">&#9760;</span></span>
                                <br>
                                <span class="content">
                                    {{ $w->word }}
                                </span>
                            </span>
                            <span class="right-arrow" onclick="changePriority(event,this,-1,'{{ route('changePriority', ['ru' => $word->id, 'en' => $w->id, 'num' => -1]) }}');">&#10095;</span>
                        </span>
                    @endforeach
                    </div>
                    <br>
                    <div class="search">
                        <input
                               class="search"
                               onkeyup="search(this)"
                               type="text" name="search"
                               placeholder="Find some words">

                        <ul class='list' data-word_id="{{$word->id}}">
                        </ul>
                    </div>
                </td>

{{--            @if( $word->known <= 4)--}}
{{--                <td class="">--}}
{{--                    <textarea name="hint"  rows="4" >{{ $word->englishWords->implode('word',", ") }}</textarea>--}}
{{--                </td>--}}
{{--            @else--}}
{{--                <td class="hint">--}}
{{--                    <textarea name="hint"  rows="4" >{{ $word->englishWords->implode('word',", ") }}</textarea>--}}
{{--                </td>--}}
{{--            @endif--}}
            <td class="word" width="25%">
                <span class="little-word">{{ $word->word }}</span>
            </td>
            <td width="25%">
                <span class="change-button down"
                      onclick="changeVisible(event)"><!-- translate show -1 --> &#10224;</span>
                <span class="change-button down"

                      onclick="check(event, '{{ route('learn', ['id' => $word->id, 'known' => ($word->known < 2 ? 0 : $word->known - 1)]) }}')"><!-- CHANGE -1 --> &#10094;</span>
{{--                <span class="change-button down"--}}
{{--                      onclick="hide(event)"><!-- SKIP -->  &#9866;</span>--}}
                <span class="change-button up"
                      onclick="check(event, '{{ route('learn', ['id' => $word->id, 'known' => ($word->known + 1)]) }}')"><!-- CHANGE +1 --> &#10095;</span>
                <span class="change-button know"
                      onclick="check(event, '{{ route('learn', ['id' => $word->id, 'known' => ($word->known + 5)]) }}')"><!--  KNOW +5 --> &#10004;</span>
{{--                <span class="change-button know"--}}
{{--                      onclick="change(event, '/change/{{ $word->id }}')"> <!--CHANGE --> &#10000;</span>--}}
                <span class="change-button delete"
                      onclick="check(event, '{{ route('down', ['id' => $word->id]) }}')"><!-- DELETE --> &#8681;</span>
            </td>
        </tr>
        @endforeach
    </table>
    <template id="word_template">
        <span class="little-word">
            <span class="left-arrow" >&#10094;</span>
            <span class="middle">
                <span class="up-button"><span class="cross" >&#9760;</span></span>
                <br>
                <span class="content">
                    woooord
                </span>
            </span>
            <span class="right-arrow">&#10095;</span>
        </span>
    </template>
</div>


<script type="text/javascript">
    // document.getElementsByClassName(".change-button").addEventListener(
    //   "mouseover",
    //   (event) => {
    //       console.log("hiho");
    //     // highlight the mouseenter target
    //     event.target.style.color = "purple";
    //     // reset the color after a short delay
    //     setTimeout(() => {
    //       event.target.style.color = "";
    //     }, 500);
    //   },
    //   false,
    // );
    function changeVisible(e){
        e.target.closest("tr").children[1].classList.remove("translate");
        // element.classList.remove("mystyle");
    }

    function check(e, url){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.send();
        xhr.onload = function() {
            e.target.closest('tr').remove();
        };
        xhr.onerror = function() {
          alert("Запрос не удался");
        };
    }
    function hide(e){
        e.target.closest('tr').remove();
    }
    function change(e, url){
        let xhr = new XMLHttpRequest();
        xhr.open('POST', url);
        let formData = new FormData();
        let currentRow = e.target.closest('tr')

        formData.append('word', currentRow.querySelector('textarea[name="translate"]').value)
        formData.append('translate', currentRow.querySelector('textarea[name="word"]').value)
        formData.append('hint', currentRow.querySelector('textarea[name="hint"]').value)

        xhr.send(formData);
        xhr.onload = function() {

        };
        xhr.onerror = function() {
          alert("Запрос не удался");
        };
    }
    function changePriority(event, elem, vector, url){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.send();
        xhr.onload = function() {
            swapElem(event,elem,vector);
        };
        xhr.onerror = function() {
            alert("Запрос не удался");
        };
    }
    function detach(e, url){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.send();
        xhr.onload = function() {
            e.target.closest('.little-word').remove();
        };
        xhr.onerror = function() {
            alert("Запрос не удался");
        };
    }
    function swapElem(event, elem, vector) {
        let parent = elem.parentNode.parentNode;
        let current = elem.parentNode;
        let prev = 0
        let cur = 0
        let next = 0
        let children = parent.children
        for (let item of children) {
            next += 1
            if(item == current){
                prev = next-2
                cur = next-1
                break;
            }
        }

        let from = children[cur]
        let to = children[prev]
        if(vector < 0){
            from = children[next]
            to = children[cur]
        }
        if(from && to){
            parent.insertBefore(from, to);
        }
    }
    function search(elem) {
        let input = elem.value
        input = input.toLowerCase();
        if(input.length > 2) {
            createXhrRequest("GET", "/search?string=" + input, function (err, response) {
                if (err) {
                    console.log("Error!");
                }
                let ul = elem.parentNode.querySelector('.list');
                let list = JSON.parse(response)
                ul.innerHTML = ""
                list.forEach(function (row, ind) {
                    let li = document.createElement('li');
                    li.value = row.id
                    li.onclick = function () {
                        associateWord(this);
                    };
                    li.innerText = row.word
                    ul.appendChild(li)
                });
            });
        } else {
            let ul = elem.parentNode.querySelector('.list');
            ul.innerHTML = ""
        }
    }
    function associateWord(elem){
        let en = elem.value
        let ru = elem.parentNode.dataset.word_id
        let template = document.getElementById("word_template")
        let clone = template.content.cloneNode(true);
        let content = clone.querySelector(".content");

        createXhrRequest( "GET", "/associate/"+ru+"/"+en, function(err, response) {
            if( err ) {
                console.log( "Error!" );
            }
            content.textContent = elem.innerText;
            elem.parentNode.parentNode.parentNode.querySelector('.content').appendChild(clone);
        });


    }
    var createXhrRequest = function( httpMethod, url, callback ) {
        var xhr = new XMLHttpRequest();
        xhr.open( httpMethod, url );
        xhr.onload = function() {
            callback( null, xhr.response );
        };
        xhr.onerror = function() {
            callback( xhr.response );
        };
        xhr.send();
    }

</script>
{{--@section('styles')--}}
    <style type="text/css">
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 15px;
        }
        td.word {
            background-color:#ffe6e6;
        }

        textarea {
            background-color:transparent;

            font-size: 25px;
            border: none;

            outline: none;
            resize:none;
            overflow: hidden;
            height: 100%;

        }
        tr:hover {
            background-color:#d6f5d6;
        }
        td.translate {
            background-color:#d6f5d6;
            opacity: 0;
        }
        td.translate:hover {
            opacity: 100%;
        }
        td.translate > textarea {
            opacity: 100%;
        }
        /*td.translate:hover > textarea {*/
        /*    color: black;*/
        /*}*/
        /*td.hint > textarea {*/
        /*    color: transparent;*/
        /*}*/
        /*td.hint:hover > textarea {*/
        /*    color: black;*/
        /*}*/
        .delete{
            color:red;
        }
        .change-button {
            display:inline-block;
            padding: 5px 20px;
            border-right:1px solid grey;
            font-size: 25px;
        }
        .change-button:hover {
            cursor:pointer;
            color: grey;
            text-decoration: underline red;
            background-color: rgb(250,250,250);
        }
        .little-word {
            display: inline-block;
            border: 1px solid lightgray;
            /*background-color: lightblue;*/
            padding: 2px;
            margin: 1px;
            font-size: 25px;
        }

        .left-arrow {
            border: 1px solid transparent;
            height: 100%;
            padding: 5px;
        }
        .left-arrow:hover {
            border: 1px solid grey;
            background: #9ca3af;
            cursor: pointer;
        }

        .right-arrow {
            border: 1px solid transparent;
            height: 100%;
            padding: 5px;
        }
        .right-arrow:hover {
            border: 1px solid grey;
            background: #9ca3af;
            cursor: pointer;
        }
        .up-button {
            display: inline-block;
            text-align: center;
            width: 100%;
            font-size: 25px;
            color: red;
        }
        .cross:hover {
            border:1px solid black;
            cursor: pointer;
            background: grey;
        }
        .content {
            display: inline-block;
        }
        .middle {
            display: inline-block;
        }
        .primary {
            background: lightgreen;
        }
        .secondary {
            background: rgba(255, 255, 0, 0.52);
        }
        .secondary+.rare {
            opacity: 20%;
        }
        .list li {
            border: 1px solid transparent;
            font-size: 20px;
            color: #0a0a0a;
        }
        .list li:hover {
            /*border: 1px solid black;*/
            text-decoration: underline;
            color: orange;
        }
    </style>
@endsection

$(document).ready(function () {
    let article = new Article();
    let articleAjax = new ArticleAjax();
    let translator = new Translator();
    let position = new Position();

    /** Выбор категории, в которой создавать запись. */
    article.setGetParams();

    /** Рендер формы в модальном окне для создания статьи через ajax */
    articleAjax.create();

    /** Рендер формы в модальном окне для редактирования статьи через ajax */
    articleAjax.update();

    /** Транслит для категории, при создании. */
    translator.category();

    /**
     * Транслит из поля c классом `translate-of` в поля с классом `translate-in` при создании записи.
     * Поля с данными классами должны быть в единственном экземляре на странице.
     */
    translator.article();

    /** Отображение дополнительных полей в форме `Type` в зависимости от того, какой чекбокс был выбран */
    Different.formDisplay();

    /** Диалоговое окно с подтверждением удаления. */
    Different.confirmDelete();

    /** Сдвигаем строку вверх/вниз с помощью плагина "position". */
    position.positionMove();

});

/** Работа с категориями в админ-панели */
class Article {

    /** Передача get параметров в адресную строку с обновлением страницы. */
    setGetParams() {
        let categoryId = $(".category-id");
        let categoryType = $(".category-type");

        categoryId.on("change", function () {
            let categoryId = $(this).val();

            if (categoryId !== "") {
                location.href = "?category_id=" + $(this).val();
            } else {
                location.href = location.origin + location.pathname;
            }
        });

        categoryType.on("change", function () {
            let url = new URL(location.href);
            let type = $(this).val();

            categoryId = url.searchParams.get("category_id");

            if (type !== "") {
                location.href = "?category_id=" + categoryId + "&type=" + type;
            } else {
                location.href = "?category_id=" + categoryId;
            }
        })
    }
}

/** Вывод формы создания/редактирования статьи в модальном окне через ajax. */
class ArticleAjax {

    /** Вывод формы для создания статьи. */
    create() {
        let self = this;
        let button = $(".create-article");

        button.on("click", function () {
            let data = {
                categoryId: $(this).attr("data-category-id"),
                type: $(this).attr("data-type"),
                currentUrl: location.href,
            };

            self.renderModal("/dynamic-page/article/create-ajax", data);
        })
    }

    /** Вывод формы для редактирования статьи. */
    update() {
        let self = this;
        let button = $(".update-article");

        button.on("click", function (event) {
            event.preventDefault();
            let data = {
                id: $(this).attr("data-article-id"),
                currentUrl: location.href,
            };

            self.renderModal("/dynamic-page/article/update-ajax", data);
        })
    }

    /** Добавляем на страницу модальное окно и помещаем в него форму для создания/редактирования статьи. */
    renderModal(url, data) {
        $.ajax({url: "/dynamic-page/article/modal-render", type: "get", data: {}}).done(
            (result) => {
                $("body").prepend(result);

                $.ajax({url: url, type: "get", data: data}).done(
                    (result) => {
                        let modal = $("#dynamic-page-modal");

                        modal.find(".modal-content").append(result);
                        modal.modal("show");
                        modal.on("hidden.bs.modal", function () {
                            this.remove();
                        })
                    }
                )
            }
        );
    }
}

/** Транслитерация из кириллицы в латиницу. */
class Translator {

    /** Транслит для категории, при создании. */
    category() {
        let self = this;
        let createCategory = $(".kv-create");
        let createRoot = $(".kv-create-root");

        createCategory.on("click", function () {
            self.translate();
        });
        createRoot.on("click", function () {
            self.translate();
        });
    }

    /**
     * Транслит из поля c классом `translate-of` в поля с классом `translate-in` при создании записи.
     * Поля с данными классами должны быть в единственном экземляре на странице.
     */
    article() {
        let self = this;
        let modal = $(".create-article");
        let translateOf = $(".translate-of");

        if (translateOf.length !== 0) { /** Подключаем в админ-панели */
            self.translate();
        } else { /** Подключаем вне админ-панели, при вызове модального окна на создание записи */
            modal.on("click", function () {
                self.translate();
            });
        }
    }

    /** Установка обработчика событий. */
    translate() {
        let self = this;
        setTimeout(function () {
            let translateOf = $(".translate-of");
            let translateIn = $(".translate-in");

            if (translateOf.val() === "") {
                translateOf.on("keyup", function () {
                    self.convert(translateOf, translateIn);
                });
            }
        }, 500);
    }

    /**
     * Конвертирует транслитом кириллицу в латинские символы.
     *
     * @param translateOf из данного поля получаем символы.
     * @param translateIn в данное поле вставляем транслитерированные символы.
     * @returns {boolean}
     */
    convert(translateOf, translateIn) {
        let cyrillic = translateOf.val();
        let latin;
        let newStr = "";
        let symbols = {
            'а':'a', 'б':'b', 'в':'v', 'г':'g', 'д':'d', 'е':'e', 'ё':'jo', 'ж':'zh', 'з':'z', 'и':'i', 'й':'j', 'к':'k',
            'л':'l', 'м':'m', 'н':'n', 'о':'o', 'п':'p', 'р':'r', 'с':'s', 'т':'t', 'у':'u', 'ф':'f', 'х':'h', 'ц':'c',
            'ч':'ch', 'ш':'sh', 'щ':'shch', 'ъ':'', 'ы':'y', 'ь':'', 'э':'e', 'ю':'ju', 'я':'ja', ' ':'-', ';':'', ':':'',
            ',':'', '—':'-', '–':'-', '.':'', '«':'', '»':'', '"':'', "'":'', '@':''
        };

        cyrillic = cyrillic.toLowerCase().replace(/<.+>/, ' ').replace(/\s+/, ' ');
        for (let index = 0; index < cyrillic.length; index++) {
            latin = cyrillic.charAt(index);
            newStr += latin in symbols ? symbols[latin] : latin;
        }

        translateIn.val(newStr);
    }
}

/** Реализация ajax перемещения записей для виджета Position. */
class Position {

    /** Сдвигаем строку вверх/вниз с помощью плагина "position". */
    positionMove() {
        let self = this;
        let buttonMove = $(".move-jquery");

        buttonMove.on("click", function (event) {
            let row = $("tr[data-key=" + $(this).attr("data-id") + "]");

            event.preventDefault();
            event.stopImmediatePropagation();

            $.ajax({url: $(this).attr("href"), type: "POST", data: {ajax: "true"}}).done(
                (result) => {
                    if (result === "1") {
                        self.markButton(buttonMove, $(this));
                        row.prev().before(row);
                    } else if (result === "-1") {
                        self.markButton(buttonMove, $(this));
                        row.next().after(row);
                    }
                }
            )
        });
    }

    /** Нажатая кнопка позиции изменяет свой цвет. */
    markButton(buttonAll, buttonCurrent) {
        buttonAll.css({color: ""});
        buttonCurrent.css({color: "red"});
    }
}

/** Различные независимые функции. */
class Different {

    /** Отображение дополнительных полей в форме `Type` в зависимости от того, какой чекбокс был выбран */
    static formDisplay() {
        let checkboxCategory = $(".category-checkbox");
        let checkboxArticle = $(".article-checkbox");
        let forCategory = $(".for-category");
        let forArticle = $(".for-article");

        if (checkboxCategory.prop("checked") === true) {
            forCategory.css({display: "block"});
        }
        if (checkboxArticle.prop("checked") === true) {
            forArticle.css({display: "block"});
        }

        checkboxCategory.on("change", function () {
            if ($(this).prop("checked") === true) {
                forCategory.css({display: "block"});
            } else {
                forCategory.css({display: "none"});
            }
        });
        checkboxArticle.on("change", function () {
            if ($(this).prop("checked") === true) {
                forArticle.css({display: "block"});
                $(".select2-search__field").css({width: "100%"}); // фикс ширины.
            } else {
                forArticle.css({display: "none"});
            }
        })
    }

    /** Диалоговое окно с подтверждением удаления. */
    static confirmDelete() {
        let button = $("[data-confirm-delete]");

        button.on("click", function (event) {
            let answer = confirm("Вы уверены, что хотите удалить этот элемент?");

            if (answer === false) {
                event.preventDefault();
            }
        });
    }
}
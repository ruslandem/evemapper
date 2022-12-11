(function ($) {
    $.fn.signaturesTable = function (options) {
        var settings = $.extend(
            {
                highlightNewSignatures: true,
            },
            options
        );

        this.init = () => {
            if (!window.hdate) {
                throw "human-date instance (window.hdate) not found";
            }
            return this;
        };

        /**
         * Gets signatures and populates them in the table.
         */
        this.show = () => {
            $.get({
                url: settings.url.get,
            })
                .done((response) => {
                    if (response.data) {
                        populate(response.data);
                    }
                })
                .fail(() => {
                    throw "failed to get signatures";
                });
        };

        /**
         * Updates cosmic signatures using clipboard data.
         * @param {Object} options Options should contain `solarSystem` and `replace` properties
         */
        this.update = (options) => {
            navigator.clipboard.readText().then((text) => {
                $.post({
                    url: settings.url.update,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        solarSystemName: options.solarSystem,
                        text: text,
                        replace: options.replace ?? false,
                    },
                })
                    .done((response) => {
                        if (response) {
                            Toastify({
                                text: `${response.updated ?? 0} added and ${
                                    response.created ?? 0
                                } updated signatures`,
                                duration: 3000,
                            }).showToast();
                            this.show();
                        }
                    })
                    .fail(() => {
                        throw "failed to update signatures";
                    });
            });
        };

        /**
         * Deletes signature by id.
         * @param {Object} options Options contains `solarSystem` and `id`.
         */
        this.delete = (options) => {
            $.post({
                type: "delete",
                url: settings.url.delete,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    solarSystemName: options.solarSystem,
                    signatureId: options.id,
                },
            }).done((response) => {
                if (response) {
                    Toastify({
                        text: `Deleted signature ${options.id}`,
                        duration: 3000,
                    }).showToast();
                    this.show();
                }
            });
        };

        /**
         * Populates table with the array of fetched signatures.
         * @param {Array} data
         */
        var populate = (data) => {
            const body = $(this).find("tbody").first();
            const oldSignatures = body
                .find("td:nth-child(1)")
                .map(() => {
                    return $(this).text();
                })
                .get();
            body.text("");
            data.forEach((element) => {
                const createdTime = window.hdate.prettyPrint(
                    element.created_at,
                    {
                        showTime: true,
                    }
                );
                const relativeTime = window.hdate.relativeTime(
                    element.created_at
                );

                let row = $(`<tr></tr>`);
                if (
                    settings.highlightNewSigantures &&
                    !oldSignatures.includes(element.signatureId)
                ) {
                    row.addClass("new-signature");
                }
                row.append(`<td>${element.signatureId}</td>`);
                row.append(`<td>${element.groupName}</td>`);
                row.append(`<td>${element.signatureName}</td>`);
                row.append(`<td title="${createdTime}">${relativeTime}</td>`);
                row.append(`<td><button class="delete"></button></td>`);
                body.append(row);
            });
        };

        return this.init();
    };
})(jQuery);

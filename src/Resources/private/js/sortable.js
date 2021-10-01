import Sortable, {Swap} from "sortablejs";

Sortable.mount(new Swap());

if (typeof ENTITY_NAMESPACE === "undefined") {
    alert('Entity not found');
}

let alertsContainer = document.getElementById('alerts-container');

let items = document.querySelectorAll('.sortable');
for(let i = 0; i < items.length; i++){
    let sortable = Sortable.create(items[i], {
        group: 'nested',
        fallbackOnBody: true,
        swapThreshold: 0.65,
        handle: '.move',
        draggable: '.sortable-item',
        animation: 150,
        dataIdAttr: 'data-id',
        store: {
            set: function (sortable) {
                let order = sortable.toArray();
                console.log(order);
                let formData = new FormData();
                formData.append('sortableData', order);
                formData.append('entity', ENTITY_NAMESPACE)

                fetch(Routing.generate('te_sortable_sort'), {
                    method: "post",
                    credentials: 'same-origin',
                    body: formData,
                    headers: new Headers({
                        'X-Requested-With': 'XMLHttpRequest'
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(response => {
                        if (response.error) {
                            throw new Error(response.error);
                        }

                        alertsContainer.innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.done + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    })
                    .catch(error => {
                        alertsContainer.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' + error + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    })
                ;
            }
        }
    });
}


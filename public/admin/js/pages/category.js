
options_select2 = {
	parent_id: {
		selector: $('select[name="parent_id"]'),
		placeholder: 'Chọn danh mục cha',
		multiple: false,
		hide_search: false,
		url: url_ajax_load
	}
}

$(function () {
	//load lang
	load_lang(controller);
	DATATABLE.init();
	TINYMCE.init();
	SEO.init();
});

function save() {
	let url = save_method === 'add' ? url_ajax_add : url_ajax_update;
	AJAX.save(url, $('#form'), reload_table);
}

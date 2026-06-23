(function ($) {
	'use strict';

	var state = {
		layout: (VB_DATA.layout && VB_DATA.layout.rows) ? VB_DATA.layout.rows : (VB_DATA.layout && VB_DATA.layout.length ? VB_DATA.layout : []),
		globals: (VB_DATA.layout && VB_DATA.layout.globals) ? VB_DATA.layout.globals : { primary_color: '#7c3aed', secondary_color: '#111827', font_family: 'inherit', content_width: '1200', button_radius: '4' },
		history: [],
		future: [],
		selected: null, // { rowId, colId, modId }
		pendingRowCols: null,
		pendingModuleTarget: null, // { rowId, colId }
		device: 'desktop',
		blocks: VB_DATA.blocks || [],
		clientMode: false
	};

	var uid = function () {
		return 'id_' + Math.random().toString(36).substr(2, 9);
	};

	function clone(obj) { return JSON.parse(JSON.stringify(obj)); }
	function snapshot() { state.history.push({ layout: clone(state.layout), globals: clone(state.globals) }); if (state.history.length > 40) state.history.shift(); state.future = []; updateHistoryButtons(); }
	function restore(snap) { state.layout = clone(snap.layout); state.globals = clone(snap.globals); renderCanvas(); updateHistoryButtons(); }
	function updateHistoryButtons() { $('#vb-undo-btn').prop('disabled', !state.history.length); $('#vb-redo-btn').prop('disabled', !state.future.length); }

	/* ---------------------------------------------------------------- */
	/* Rendering the canvas from state.layout                            */
	/* ---------------------------------------------------------------- */

	function renderCanvas() {
		var $canvas = $('#vb-canvas');
		$canvas.find('.vb-row').remove();
		$canvas.css({ '--vb-primary': state.globals.primary_color || '#7c3aed', '--vb-secondary': state.globals.secondary_color || '#111827', '--vb-content-width': (state.globals.content_width || '1200') + 'px', '--vb-button-radius': (state.globals.button_radius || '4') + 'px', 'font-family': state.globals.font_family || 'inherit' });

		if (!state.layout.length) {
			$('#vb-empty-state').show();
		} else {
			$('#vb-empty-state').hide();
		}

		state.layout.forEach(function (row) {
			$canvas.append(renderRow(row));
		});

		initSortables();
	}

	function renderRow(row) {
		var $row = $('<div class="vb-row" data-row-id="' + row.id + '"></div>');
		$row.append(
			'<div class="vb-hover-toolbar vb-row-toolbar">' +
			'<button class="vb-row-save-block" title="Save as Reusable Block"><span class="dashicons dashicons-download"></span></button>' +
			'<button class="vb-row-duplicate" title="Duplicate Row"><span class="dashicons dashicons-admin-page"></span></button>' +
			'<button class="vb-row-settings" title="Row Settings"><span class="dashicons dashicons-admin-generic"></span></button>' +
			'<button class="vb-row-delete" title="Delete Row"><span class="dashicons dashicons-trash"></span></button>' +
			'</div>'
		);
		var $inner = $('<div class="vb-row-inner" data-row-id="' + row.id + '"></div>');
		row.columns.forEach(function (col) {
			$inner.append(renderColumn(row.id, col, row.columns.length));
		});
		$row.append($inner);
		return $row;
	}

	function renderColumn(rowId, col, totalCols) {
		var width = (100 / totalCols).toFixed(2);
		var $col = $(
			'<div class="vb-col" data-row-id="' + rowId + '" data-col-id="' + col.id + '" style="width:' + width + '%;"></div>'
		);
		$col.append(
			'<div class="vb-hover-toolbar vb-col-toolbar">' +
			'<button class="vb-col-add-module" title="Add Module"><span class="dashicons dashicons-plus"></span></button>' +
			'</div>'
		);
		if (!col.modules.length) {
			$col.addClass('vb-col-empty');
		}
		col.modules.forEach(function (mod) {
			$col.append(renderModule(rowId, col.id, mod));
		});
		return $col;
	}

	function renderModule(rowId, colId, mod) {
		var def = VB_DATA.modules[mod.type] || {};
		var label = def.label || mod.type;
		var $mod = $(
			'<div class="vb-module vb-visibility-' + ((mod.settings && mod.settings.visibility) || 'all') + '" data-row-id="' + rowId + '" data-col-id="' + colId + '" data-mod-id="' + mod.id + '" data-type="' + mod.type + '"></div>'
		);
		$mod.append(
			'<div class="vb-hover-toolbar vb-mod-toolbar">' +
			'<span style="color:#9ca3af;padding:5px 8px;font-size:11px;">' + label + '</span>' +
			'<button class="vb-mod-duplicate" title="Duplicate"><span class="dashicons dashicons-admin-page"></span></button>' +
			'<button class="vb-mod-delete" title="Delete"><span class="dashicons dashicons-trash"></span></button>' +
			'</div>'
		);
		$mod.append(moduleHtmlPreview(mod));
		return $mod;
	}

	/* Lightweight live preview markup matching frontend renderer */
	function moduleHtmlPreview(mod) {
		var s = mod.settings || {};
		switch (mod.type) {
			case 'text':
				return '<div style="color:' + (s.text_color || '#333') + ';font-size:' + responsiveFont(s, 16) + 'px;text-align:' + (s.align || 'left') + ';padding:8px;">' + (s.content || '') + '</div>';
			case 'heading':
				return '<div style="color:' + (s.text_color || '#1a1a1a') + ';font-size:' + responsiveFont(s, 36) + 'px;text-align:' + (s.align || 'left') + ';font-weight:700;padding:8px;">' + escapeHtml(s.content || '') + '</div>';
			case 'button':
				return '<div style="text-align:' + (s.align || 'left') + ';padding:8px;"><span style="display:inline-block;padding:10px 22px;border-radius:4px;background:' + (s.bg_color || 'var(--vb-primary)') + ';border-radius:var(--vb-button-radius);color:' + (s.text_color || '#fff') + ';font-size:13px;">' + escapeHtml(s.text || 'Click Here') + '</span></div>';
			case 'image':
				return s.src ? '<div style="padding:8px;"><img src="' + escapeAttr(s.src) + '" style="width:' + (s.width || 100) + '%;display:block;" /></div>' : emptyModulePlaceholder('Image — click to set source');
			case 'video':
				return s.url ? '<div style="padding:8px;color:#666;font-size:12px;">Video: ' + escapeHtml(s.url) + '</div>' : emptyModulePlaceholder('Video — click to add URL');
			case 'spacer':
				return '<div style="height:' + (s.height || 40) + 'px;background:repeating-linear-gradient(45deg,#f4f4f5,#f4f4f5 10px,#fafafa 10px,#fafafa 20px);"></div>';
			case 'gallery':
				var imgs = (s.images || []).slice(0, 6).map(function (src) {
					return '<img src="' + escapeAttr(src) + '" style="width:60px;height:60px;object-fit:cover;border-radius:4px;" />';
				}).join('');
				return '<div style="padding:8px;display:flex;gap:6px;flex-wrap:wrap;">' + (imgs || emptyModulePlaceholder('Gallery — click to add images')) + '</div>';
			case 'form':
				return '<div style="padding:18px;background:' + (s.bg_color || '#f8fafc') + ';border-radius:8px;"><strong>' + escapeHtml(s.title || 'Get in touch') + '</strong><div style="display:grid;gap:8px;margin-top:10px;"><input disabled placeholder="Name" /><input disabled placeholder="Email" />' + ((s.show_phone || 'yes') === 'yes' ? '<input disabled placeholder="Phone" />' : '') + '<textarea disabled placeholder="Message"></textarea><button disabled>' + escapeHtml(s.button_text || 'Send Message') + '</button></div></div>';
			case 'tabs':
				return '<div style="border:1px solid #e4e4e7;border-radius:8px;overflow:hidden;"><div style="display:flex;background:#f8fafc;"><b style="padding:10px;flex:1;">' + escapeHtml(s.tab_1_title || 'Tab One') + '</b><b style="padding:10px;flex:1;">' + escapeHtml(s.tab_2_title || 'Tab Two') + '</b><b style="padding:10px;flex:1;">' + escapeHtml(s.tab_3_title || 'Tab Three') + '</b></div><div style="padding:14px;">' + escapeHtml(s.tab_1_content || '') + '</div></div>';
			case 'accordion':
				return '<div style="padding:8px;"><details open><summary>' + escapeHtml(s.item_1_title || 'Question One') + '</summary><div>' + escapeHtml(s.item_1_content || '') + '</div></details><details><summary>' + escapeHtml(s.item_2_title || 'Question Two') + '</summary></details><details><summary>' + escapeHtml(s.item_3_title || 'Question Three') + '</summary></details></div>';
			case 'slider':
				var h = responsiveValue(s, 'height', 280); var img = (s.images || [])[0];
				return '<div style="height:' + h + 'px;background:#111827;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;background-size:cover;background-position:center;' + (img ? 'background-image:url(' + img + ');' : '') + '">' + (img ? '<span style="background:rgba(0,0,0,.55);padding:8px;border-radius:5px;">' + escapeHtml(s.caption || 'Slider') + '</span>' : 'Slider — click to add images') + '</div>';
			case 'countdown':
				return '<div style="padding:20px;border-radius:10px;background:' + (s.bg_color || '#111827') + ';color:' + (s.text_color || '#fff') + ';text-align:center;"><strong>' + escapeHtml(s.label || 'Offer ends in') + '</strong><div style="font-size:28px;font-weight:800;margin-top:8px;">00 : 00 : 00 : 00</div></div>';
			case 'pricing':
				return '<div style="border:1px solid #e4e4e7;border-radius:12px;padding:20px;text-align:center;"><h3>' + escapeHtml(s.plan_name || 'Starter') + '</h3><div style="font-size:34px;font-weight:800;">' + escapeHtml(s.price || '£49') + '<small>' + escapeHtml(s.period || '/month') + '</small></div><p style="white-space:pre-line;">' + escapeHtml(s.features || '') + '</p><span style="display:inline-block;background:' + (s.accent_color || 'var(--vb-primary)') + ';color:#fff;padding:10px 18px;border-radius:var(--vb-button-radius);">' + escapeHtml(s.button_text || 'Choose Plan') + '</span></div>';
			case 'testimonial':
				return '<div style="border:1px solid #e4e4e7;border-radius:12px;padding:18px;display:flex;gap:14px;align-items:center;">' + (s.image ? '<img src="' + escapeAttr(s.image) + '" style="width:64px;height:64px;border-radius:50%;object-fit:cover;" />' : '') + '<div><div>★★★★★</div><blockquote style="margin:6px 0;">' + escapeHtml(s.quote || '') + '</blockquote><strong>' + escapeHtml(s.name || '') + '</strong><br><small>' + escapeHtml(s.role || '') + '</small></div></div>';
			case 'faq_schema':
				return '<div style="padding:8px;"><strong>FAQ Schema</strong><details open><summary>' + escapeHtml(s.item_1_title || 'Question one') + '</summary><div>' + escapeHtml(s.item_1_content || '') + '</div></details><details><summary>' + escapeHtml(s.item_2_title || 'Question two') + '</summary></details><details><summary>' + escapeHtml(s.item_3_title || 'Question three') + '</summary></details></div>';
			case 'popup':
				return '<div style="padding:20px;border:1px solid #e4e4e7;border-radius:12px;text-align:center;background:#fafafa;"><strong>' + escapeHtml(s.title || 'Special Offer') + '</strong><p>' + escapeHtml(s.content || 'Popup content') + '</p><span style="display:inline-block;background:var(--vb-primary);color:#fff;padding:9px 16px;border-radius:var(--vb-button-radius);">' + escapeHtml(s.cta_text || 'Learn More') + '</span><small style="display:block;margin-top:8px;color:#71717a;">Trigger: ' + escapeHtml(s.trigger || 'timed') + '</small></div>';
			default:
				return emptyModulePlaceholder('Unknown module');
		}
	}

	function emptyModulePlaceholder(text) {
		return '<div style="padding:24px;text-align:center;color:#a1a1aa;font-size:12px;border:1px dashed #e4e4e7;">' + text + '</div>';
	}

	function escapeHtml(str) {
		return $('<div>').text(str).html();
	}

	function responsiveFont(s, fallback) {
		var key = state.device === 'mobile' ? 'font_size_mobile' : (state.device === 'tablet' ? 'font_size_tablet' : 'font_size');
		return s[key] || s.font_size || fallback;
	}

	function responsiveValue(s, base, fallback) {
		var key = state.device === 'mobile' ? base + '_mobile' : (state.device === 'tablet' ? base + '_tablet' : base);
		return s[key] || s[base] || fallback;
	}

	/* ---------------------------------------------------------------- */
	/* Sortable (drag & drop) wiring                                     */
	/* ---------------------------------------------------------------- */

	function initSortables() {
		// Rows reorder within canvas
		new Sortable(document.getElementById('vb-canvas'), {
			animation: 150,
			handle: '.vb-row-toolbar',
			draggable: '.vb-row',
			onEnd: syncOrderFromDom,
		});

		// Modules can move within/between columns
		document.querySelectorAll('.vb-col').forEach(function (colEl) {
			new Sortable(colEl, {
				group: 'vb-modules',
				animation: 150,
				draggable: '.vb-module',
				onEnd: syncOrderFromDom,
			});
		});
	}

	/* After any drag, rebuild state.layout order from actual DOM order */
	function syncOrderFromDom() {
		snapshot();
		var newLayout = [];
		$('#vb-canvas > .vb-row').each(function () {
			var rowId = $(this).data('row-id');
			var rowData = findRow(rowId);
			if (!rowData) return;

			var newCols = [];
			$(this).find('.vb-col').each(function () {
				var colId = $(this).data('col-id');
				var colData = findCol(rowId, colId) || findColAnywhere(colId);
				if (!colData) return;

				var newMods = [];
				$(this).find('.vb-module').each(function () {
					var modId = $(this).data('mod-id');
					var modData = findModAnywhere(modId);
					if (modData) newMods.push(modData);
				});
				colData.modules = newMods;
				newCols.push(colData);
			});
			rowData.columns = newCols;
			newLayout.push(rowData);
		});
		state.layout = newLayout;
		renderCanvas();
	}

	function findRow(rowId) {
		return state.layout.find(function (r) { return r.id === rowId; });
	}
	function findCol(rowId, colId) {
		var row = findRow(rowId);
		if (!row) return null;
		return row.columns.find(function (c) { return c.id === colId; });
	}
	function findColAnywhere(colId) {
		for (var i = 0; i < state.layout.length; i++) {
			var c = state.layout[i].columns.find(function (c) { return c.id === colId; });
			if (c) return c;
		}
		return null;
	}
	function findModAnywhere(modId) {
		for (var i = 0; i < state.layout.length; i++) {
			for (var j = 0; j < state.layout[i].columns.length; j++) {
				var m = state.layout[i].columns[j].modules.find(function (m) { return m.id === modId; });
				if (m) return m;
			}
		}
		return null;
	}

	/* ---------------------------------------------------------------- */
	/* Adding rows / modules                                              */
	/* ---------------------------------------------------------------- */

	function addRow(numCols) {
		snapshot();
		state.layout.push(makeRow(numCols));
		renderCanvas();
	}

	function makeModule(type, settings) {
		var def = VB_DATA.modules[type];
		return { id: uid(), type: type, settings: $.extend({}, def ? def.defaults : {}, settings || {}) };
	}

	function makeRow(numCols, settings) {
		var columns = [];
		for (var i = 0; i < numCols; i++) columns.push({ id: uid(), modules: [] });
		return { id: uid(), settings: $.extend({ bg_color: '', padding: '40px 20px' }, settings || {}), columns: columns };
	}

	function addModule(rowId, colId, type) {
		snapshot();
		var col = findCol(rowId, colId);
		if (!col) return;
		var def = VB_DATA.modules[type];
		col.modules.push({
			id: uid(),
			type: type,
			settings: $.extend({}, def ? def.defaults : {}),
		});
		renderCanvas();
	}

	/* ---------------------------------------------------------------- */
	/* Settings sidebar                                                   */
	/* ---------------------------------------------------------------- */

	function openSidebar(rowId, colId, modId) {
		var mod = findModAnywhere(modId);
		if (!mod) return;
		state.selected = { rowId: rowId, colId: colId, modId: modId };

		var def = VB_DATA.modules[mod.type] || { fields: {}, label: mod.type };
		$('#vb-sidebar-title').text(def.label + ' Settings');

		var $body = $('#vb-sidebar-body').empty();
		$.each(def.fields || {}, function (key, field) {
			$body.append(renderField(key, field, mod.settings[key]));
		});
		$body.append(renderField('visibility', { type: 'select', label: 'Responsive Visibility', options: ['all','desktop-only','tablet-only','mobile-only','hide-mobile','hide-tablet','hide-desktop'] }, mod.settings.visibility || 'all'));
		$body.append(renderField('animation', { type: 'select', label: 'Animation', options: ['none','fade-in','slide-up','zoom-in'] }, mod.settings.animation || 'none'));

		bindFieldEvents(mod);

		$('#vb-sidebar').addClass('open');
		highlightSelectedModule();
	}

	function renderField(key, field, value) {
		value = value !== undefined ? value : '';
		var $wrap = $('<div class="vb-field" data-key="' + key + '"></div>');
		$wrap.append('<label>' + field.label + '</label>');

		switch (field.type) {
			case 'textarea':
				$wrap.append('<textarea>' + escapeHtml(value) + '</textarea>');
				break;
			case 'text':
				$wrap.append('<input type="text" value="' + escapeAttr(value) + '" />');
				break;
			case 'number':
				$wrap.append('<input type="number" value="' + escapeAttr(value) + '" />');
				break;
			case 'color':
				$wrap.append('<input type="color" value="' + (value || '#000000') + '" />');
				break;
			case 'select':
				var $select = $('<select></select>');
				(field.options || []).forEach(function (opt) {
					$select.append('<option value="' + opt + '"' + (opt === value ? ' selected' : '') + '>' + opt + '</option>');
				});
				$wrap.append($select);
				break;
			case 'image':
				$wrap.append(
					'<div class="vb-field-image-preview">' + (value ? '<img src="' + escapeAttr(value) + '" />' : '<span style="color:#666;font-size:12px;">No image selected</span>') + '</div>' +
					'<button type="button" class="vb-field-btn vb-select-image">Select Image</button>'
				);
				break;
			case 'gallery':
				$wrap.append('<button type="button" class="vb-field-btn vb-select-gallery">Select Images (' + (value ? value.length : 0) + ')</button>');
				break;
			default:
				$wrap.append('<input type="text" value="' + escapeAttr(value) + '" />');
		}
		return $wrap;
	}

	function escapeAttr(str) {
		return (str + '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	}

	function bindFieldEvents(mod) {
		var $body = $('#vb-sidebar-body');

		$body.off('focusin', 'input, textarea, select').on('focusin', 'input, textarea, select', function () { if (!$(this).data('vb-snap')) { snapshot(); $(this).data('vb-snap', 1); } });
		$body.off('input change', 'input, textarea, select').on('input change', 'input, textarea, select', function () {
			var key = $(this).closest('.vb-field').data('key');
			mod.settings[key] = $(this).val();
			renderCanvas();
			// Re-open to keep sidebar in sync after re-render destroys nothing (sidebar is outside canvas)
		});

		$body.off('click', '.vb-select-image').on('click', '.vb-select-image', function () {
			var key = $(this).closest('.vb-field').data('key');
			openMediaPicker(false, function (urls) {
				snapshot();
				mod.settings[key] = urls[0];
				renderCanvas();
				openSidebar(state.selected.rowId, state.selected.colId, state.selected.modId);
			});
		});

		$body.off('click', '.vb-select-gallery').on('click', '.vb-select-gallery', function () {
			var key = $(this).closest('.vb-field').data('key');
			openMediaPicker(true, function (urls) {
				snapshot();
				mod.settings[key] = urls;
				renderCanvas();
				openSidebar(state.selected.rowId, state.selected.colId, state.selected.modId);
			});
		});
	}

	function openMediaPicker(multiple, callback) {
		var frame = wp.media({
			title: 'Select Image' + (multiple ? 's' : ''),
			multiple: multiple,
		});
		frame.on('select', function () {
			var selection = frame.state().get('selection').toArray();
			var urls = selection.map(function (att) { return att.toJSON().url; });
			callback(urls);
		});
		frame.open();
	}

	function closeSidebar() {
		$('#vb-sidebar').removeClass('open');
		state.selected = null;
		highlightSelectedModule();
	}

	function highlightSelectedModule() {
		$('.vb-module').removeClass('vb-selected');
		if (state.selected) {
			$('.vb-module[data-mod-id="' + state.selected.modId + '"]').addClass('vb-selected');
		}
	}

	/* ---------------------------------------------------------------- */
	/* Template library                                                   */
	/* ---------------------------------------------------------------- */

	function buildTemplateGrid() {
		var templates = [
			{ key: 'hero', label: 'Hero Opt-in', desc: 'Headline, text, button and image' },
			{ key: 'sales', label: 'Sales Page Starter', desc: 'Hero, benefits, FAQ and contact form' },
			{ key: 'portfolio', label: 'Gallery Page', desc: 'Heading, gallery and slider' }
		];
		var $grid = $('#vb-template-grid').empty();
		templates.forEach(function (tpl) {
			$grid.append('<button class="vb-template-option" data-template="' + tpl.key + '"><strong>' + tpl.label + '</strong><span>' + tpl.desc + '</span></button>');
		});
	}

	function insertTemplate(key) {
		var rows = [];
		if (key === 'hero') {
			var r = makeRow(2, { padding: '70px 20px' });
			r.columns[0].modules.push(makeModule('heading', { content: 'Build a better page in minutes', font_size: '48', font_size_tablet: '38', font_size_mobile: '30' }));
			r.columns[0].modules.push(makeModule('text', { content: 'Use this ready-made section as a fast starting point for a mobile-friendly landing page.' }));
			r.columns[0].modules.push(makeModule('button', { text: 'Get Started' }));
			r.columns[1].modules.push(makeModule('image'));
			rows.push(r);
		} else if (key === 'sales') {
			rows.push(makeRow(1, { padding: '70px 20px' }));
			rows[0].columns[0].modules.push(makeModule('heading', { content: 'A Clear Offer Headline', align: 'center', font_size: '46', font_size_tablet: '36', font_size_mobile: '28' }));
			rows[0].columns[0].modules.push(makeModule('text', { content: 'Explain the problem, promise and outcome in a concise opening section.', align: 'center' }));
			var b = makeRow(3); b.columns[0].modules.push(makeModule('text', { content: '<strong>Benefit One</strong><br>Describe the first clear benefit.' })); b.columns[1].modules.push(makeModule('text', { content: '<strong>Benefit Two</strong><br>Describe the second clear benefit.' })); b.columns[2].modules.push(makeModule('text', { content: '<strong>Benefit Three</strong><br>Describe the third clear benefit.' })); rows.push(b);
			var faq = makeRow(1); faq.columns[0].modules.push(makeModule('accordion')); rows.push(faq);
			var form = makeRow(1); form.columns[0].modules.push(makeModule('form')); rows.push(form);
		} else if (key === 'portfolio') {
			var h = makeRow(1); h.columns[0].modules.push(makeModule('heading', { content: 'Our Work', align: 'center' })); rows.push(h);
			var g = makeRow(1); g.columns[0].modules.push(makeModule('gallery')); rows.push(g);
			var sl = makeRow(1); sl.columns[0].modules.push(makeModule('slider')); rows.push(sl);
		}
		snapshot();
		state.layout = state.layout.concat(rows);
		renderCanvas();
	}

	/* ---------------------------------------------------------------- */
	/* Module picker grid (populated once)                                */
	/* ---------------------------------------------------------------- */

	function buildModuleGrid() {
		var $grid = $('#vb-module-grid').empty();
		$.each(VB_DATA.modules, function (type, def) {
			$grid.append(
				'<button class="vb-module-option" data-type="' + type + '">' +
				'<span class="dashicons ' + (def.icon || 'dashicons-screenoptions') + '"></span>' +
				def.label +
				'</button>'
			);
		});
	}


	/* ---------------------------------------------------------------- */
	/* Reusable blocks / import-export / client mode                      */
	/* ---------------------------------------------------------------- */

	function regenerateIds(obj) {
		var copy = clone(obj);
		function walkRow(row) {
			row.id = uid();
			(row.columns || []).forEach(function (c) {
				c.id = uid();
				(c.modules || []).forEach(function (m) { m.id = uid(); });
			});
		}
		if (copy.columns) walkRow(copy);
		return copy;
	}

	function buildBlocksGrid() {
		var $grid = $('#vb-blocks-grid').empty();
		if (!state.blocks.length) {
			$grid.append('<p class="vb-modal-help">No saved blocks yet. Use the save icon on a row toolbar to save a reusable section.</p>');
			return;
		}
		state.blocks.forEach(function (block) {
			$grid.append('<div class="vb-block-card"><button class="vb-template-option vb-block-insert" data-block-id="' + block.id + '"><strong>' + escapeHtml(block.name) + '</strong><span>' + escapeHtml(block.created || '') + '</span></button><button class="vb-block-delete" data-block-id="' + block.id + '">Delete</button></div>');
		});
	}

	function saveRowAsBlock(rowId) {
		var row = findRow(rowId);
		if (!row) return;
		var name = window.prompt('Name this reusable block:', 'Reusable section');
		if (!name) return;
		$.post(VB_DATA.ajaxUrl, { action: 'vb_save_block', nonce: VB_DATA.nonce, name: name, block: JSON.stringify(row) })
			.done(function (res) { if (res && res.success) { state.blocks = res.data.blocks || []; buildBlocksGrid(); alert('Reusable block saved.'); } else { alert('Could not save block.'); } });
	}

	function insertBlock(blockId) {
		var block = state.blocks.find(function (b) { return b.id === blockId; });
		if (!block || !block.block) return;
		snapshot();
		state.layout.push(regenerateIds(block.block));
		renderCanvas();
	}

	function deleteBlock(blockId) {
		if (!window.confirm('Delete this saved block?')) return;
		$.post(VB_DATA.ajaxUrl, { action: 'vb_delete_block', nonce: VB_DATA.nonce, id: blockId })
			.done(function (res) { if (res && res.success) { state.blocks = res.data.blocks || []; buildBlocksGrid(); } });
	}

	function exportLayout() {
		$('#vb-io-title').text('Export Layout JSON');
		$('#vb-io-text').val(JSON.stringify({ globals: state.globals, rows: state.layout }, null, 2));
		$('#vb-io-apply').hide();
		$('#vb-io-modal').addClass('open');
	}

	function openImport() {
		$('#vb-io-title').text('Import Layout JSON');
		$('#vb-io-text').val('');
		$('#vb-io-apply').show();
		$('#vb-io-modal').addClass('open');
	}

	function applyImport() {
		try {
			var data = JSON.parse($('#vb-io-text').val());
			var rows = data.rows || (Array.isArray(data) ? data : []);
			if (!Array.isArray(rows)) throw new Error('Rows missing');
			snapshot();
			state.globals = data.globals || state.globals;
			state.layout = rows;
			$('#vb-io-modal').removeClass('open');
			renderCanvas();
		} catch (e) { alert('Invalid layout JSON.'); }
	}

	function toggleClientMode() {
		state.clientMode = !state.clientMode;
		$('#vb-app').toggleClass('vb-client-mode', state.clientMode);
		$('#vb-client-mode-btn').toggleClass('active', state.clientMode).text(state.clientMode ? 'Exit Client Mode' : 'Client Mode');
		closeSidebar();
	}

	/* ---------------------------------------------------------------- */
	/* Save                                                                */
	/* ---------------------------------------------------------------- */

	function saveLayout() {
		$('#vb-save-status').removeClass('success').text('Saving...');
		$.post(VB_DATA.ajaxUrl, {
			action: 'vb_save_layout',
			nonce: VB_DATA.nonce,
			post_id: VB_DATA.postId,
			layout: JSON.stringify({ globals: state.globals, rows: state.layout }),
		}).done(function (res) {
			if (res && res.success) {
				$('#vb-save-status').addClass('success').text('Saved ✓');
			} else {
				$('#vb-save-status').text('Save failed');
			}
		}).fail(function () {
			$('#vb-save-status').text('Save failed');
		});
	}

	/* ---------------------------------------------------------------- */
	/* Event bindings                                                      */
	/* ---------------------------------------------------------------- */

	$(function () {
		buildModuleGrid();
		buildTemplateGrid();
		renderCanvas();
		updateHistoryButtons();

		$('#vb-device-buttons .vb-device-btn').on('click', function () {
			state.device = $(this).data('device');
			$('#vb-device-buttons .vb-device-btn').removeClass('active');
			$(this).addClass('active');
			$('#vb-canvas').removeClass('vb-device-desktop vb-device-tablet vb-device-mobile').addClass('vb-device-' + state.device);
			renderCanvas();
		});

		$('#vb-global-btn').on('click', function () { $('#vb-global-primary').val(state.globals.primary_color || '#7c3aed'); $('#vb-global-secondary').val(state.globals.secondary_color || '#111827'); $('#vb-global-font').val(state.globals.font_family || 'inherit'); $('#vb-global-width').val(state.globals.content_width || '1200'); $('#vb-global-radius').val(state.globals.button_radius || '4'); $('#vb-global-modal').addClass('open'); });
		$('#vb-global-modal-close').on('click', function () { $('#vb-global-modal').removeClass('open'); });
		$('#vb-global-apply').on('click', function () { snapshot(); state.globals = { primary_color: $('#vb-global-primary').val(), secondary_color: $('#vb-global-secondary').val(), font_family: $('#vb-global-font').val() || 'inherit', content_width: $('#vb-global-width').val() || '1200', button_radius: $('#vb-global-radius').val() || '4' }; $('#vb-global-modal').removeClass('open'); renderCanvas(); });
		$('#vb-undo-btn').on('click', function () { if (!state.history.length) return; state.future.push({ layout: clone(state.layout), globals: clone(state.globals) }); restore(state.history.pop()); });
		$('#vb-redo-btn').on('click', function () { if (!state.future.length) return; state.history.push({ layout: clone(state.layout), globals: clone(state.globals) }); restore(state.future.pop()); });



		$('#vb-blocks-btn').on('click', function () { buildBlocksGrid(); $('#vb-blocks-modal').addClass('open'); });
		$('#vb-blocks-modal-close').on('click', function () { $('#vb-blocks-modal').removeClass('open'); });
		$(document).on('click', '.vb-block-insert', function (e) { e.preventDefault(); e.stopImmediatePropagation(); insertBlock($(this).data('block-id')); $('#vb-blocks-modal').removeClass('open'); });
		$(document).on('click', '.vb-block-delete', function (e) { e.stopPropagation(); deleteBlock($(this).data('block-id')); });
		$('#vb-export-btn').on('click', exportLayout);
		$('#vb-import-btn').on('click', openImport);
		$('#vb-io-modal-close').on('click', function () { $('#vb-io-modal').removeClass('open'); });
		$('#vb-io-apply').on('click', applyImport);
		$('#vb-client-mode-btn').on('click', toggleClientMode);

		$('#vb-template-btn').on('click', function () { $('#vb-template-modal').addClass('open'); });
		$('#vb-template-modal-close').on('click', function () { $('#vb-template-modal').removeClass('open'); });
		$(document).on('click', '.vb-template-option:not(.vb-block-insert)', function () { insertTemplate($(this).data('template')); $('#vb-template-modal').removeClass('open'); });

		$('#vb-add-row, #vb-add-row-empty').on('click', function () {
			$('#vb-row-modal').addClass('open');
		});
		$('#vb-row-modal-close').on('click', function () {
			$('#vb-row-modal').removeClass('open');
		});
		$('.vb-layout-option').on('click', function () {
			var cols = parseInt($(this).data('cols'), 10);
			addRow(cols);
			$('#vb-row-modal').removeClass('open');
		});

		$('#vb-module-modal-close').on('click', function () {
			$('#vb-module-modal').removeClass('open');
		});
		$(document).on('click', '.vb-module-option', function () {
			var type = $(this).data('type');
			if (state.pendingModuleTarget) {
				addModule(state.pendingModuleTarget.rowId, state.pendingModuleTarget.colId, type);
			}
			$('#vb-module-modal').removeClass('open');
		});

		// Delegate: column "add module" button
		$(document).on('click', '.vb-col-add-module', function (e) {
			e.stopPropagation();
			var $col = $(this).closest('.vb-col');
			state.pendingModuleTarget = { rowId: $col.data('row-id'), colId: $col.data('col-id') };
			$('#vb-module-modal').addClass('open');
		});

		// Delegate: empty column click also opens module modal
		$(document).on('click', '.vb-col-empty', function (e) {
			if ($(e.target).closest('.vb-hover-toolbar').length) return;
			var $col = $(this);
			state.pendingModuleTarget = { rowId: $col.data('row-id'), colId: $col.data('col-id') };
			$('#vb-module-modal').addClass('open');
		});

		// Delegate: click a module opens its settings sidebar
		$(document).on('click', '.vb-module', function (e) {
			if ($(e.target).closest('.vb-hover-toolbar').length) return;
			e.stopPropagation();
			var mod = findModAnywhere($(this).data('mod-id')); if (state.clientMode && mod && ['text','heading','button','image'].indexOf(mod.type) === -1) return;
			openSidebar($(this).data('row-id'), $(this).data('col-id'), $(this).data('mod-id'));
		});

		// Delegate: duplicate module
		$(document).on('click', '.vb-mod-duplicate', function (e) {
			e.stopPropagation();
			var $mod = $(this).closest('.vb-module');
			var col = findCol($mod.data('row-id'), $mod.data('col-id'));
			var mod = findModAnywhere($mod.data('mod-id'));
			if (col && mod) { snapshot(); var copy = clone(mod); copy.id = uid(); col.modules.push(copy); renderCanvas(); }
		});

		// Delegate: delete module
		$(document).on('click', '.vb-mod-delete', function (e) {
			e.stopPropagation();
			var $mod = $(this).closest('.vb-module');
			var col = findCol($mod.data('row-id'), $mod.data('col-id'));
			if (col) {
				snapshot();
				col.modules = col.modules.filter(function (m) { return m.id !== $mod.data('mod-id'); });
			}
			if (state.selected && state.selected.modId === $mod.data('mod-id')) closeSidebar();
			renderCanvas();
		});


		// Delegate: save row as reusable block
		$(document).on('click', '.vb-row-save-block', function (e) {
			e.stopPropagation();
			var $row = $(this).closest('.vb-row');
			saveRowAsBlock($row.data('row-id'));
		});

		// Delegate: duplicate row
		$(document).on('click', '.vb-row-duplicate', function (e) {
			e.stopPropagation();
			var $row = $(this).closest('.vb-row');
			var row = findRow($row.data('row-id'));
			if (row) { snapshot(); var copy = clone(row); copy.id = uid(); copy.columns.forEach(function(c){ c.id = uid(); c.modules.forEach(function(m){ m.id = uid(); }); }); state.layout.push(copy); renderCanvas(); }
		});

		// Delegate: delete row
		$(document).on('click', '.vb-row-delete', function (e) {
			e.stopPropagation();
			var $row = $(this).closest('.vb-row');
			snapshot();
			state.layout = state.layout.filter(function (r) { return r.id !== $row.data('row-id'); });
			renderCanvas();
		});

		$('#vb-sidebar-close, #vb-delete-element').on('click', function () {
			if (this.id === 'vb-delete-element' && state.selected) {
				var col = findCol(state.selected.rowId, state.selected.colId);
				if (col) {
					snapshot();
					col.modules = col.modules.filter(function (m) { return m.id !== state.selected.modId; });
				}
				renderCanvas();
			}
			closeSidebar();
		});

		$('.vb-tab').on('click', function () {
			$('.vb-tab').removeClass('active');
			$(this).addClass('active');
		});

		$('#vb-save-btn').on('click', saveLayout);
		$('#vb-exit-btn').on('click', function () {
			window.location.href = VB_DATA.editUrl;
		});
	});

})(jQuery);

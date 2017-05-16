$(document).ready(function() {
    $('.modal')
        .on('shown.bs.modal', function(e) {
            var vi_editor = $(e.target).find('[data-toggle="visual-editor"]');
            if (vi_editor.length > 0) {
                vi_editor.empty();
                nabu.loadLibrary('VE.SiteEditor', function() {
                    var editor = new Nabu.VisualEditor.SiteEditor(vi_editor[0], '/js/visual-editor/config/site-target.xml');
                    if (editor.init()) {
                        editor.enableGrid();
                        editor.enableGuides();
                        editor.enableVertexLivePreview();
                        editor.enableEdgeLayout();
                        editor.setDefaultVertexFillColor('#ffffff');
                        editor.setDefaultEdgeRounded(false);
                        editor.setDefaultEdgeTypeAsElbowConnector('vertical');

                        var data = $(vi_editor[0]).data();
                        if (data['source']) {
                            editor.load(data['source']);
                        } else {
                            editor.fillWithSample();
                        }
                    }
                });
                /*
                    if (data.source) {
                        nabu.loadLibrary('Ajax', function() {
                            var ajax = new Nabu.Ajax.Connector(data.source, 'GET');
                            ajax.addEventListener(new Nabu.Event({
                                onLoad: function(e) {
                                    var graph = editor.graph;
                                    var model = graph.getModel();
                    				graph.view.setTranslate(20, 20);

                                    graph.addListener(mxEvent.CELLS_RESIZED, function (sender, evt) {
                                        console.log(evt);
                                        if (evt.properties.cells.length > 0) {
                                            saveCellsGeometry(evt.properties.cells);
                                        }
                                    });

                                    graph.addListener(mxEvent.CELLS_MOVED, function (sender, evt) {
                                        if (evt.properties.cells.length > 0) {
                                            saveCellsGeometry(evt.properties.cells);
                                        }
                                    });

                                    graph.connectionHandler.addListener(mxEvent.START, function(sender, evt) {
                                        console.log('Start');
                                        console.log(sender);
                                        console.log(evt);
                                    });

                                    graph.connectionHandler.addListener(mxEvent.CONNECT, function(sender, evt) {
                                        console.log('Connect');
                                        console.log(sender);
                                        console.log(evt);
                                    });

                                    // Installs context menu
                                    graph.popupMenuHandler.factoryMethod = function(menu, cell, evt)
                                    {
                                        if (cell === null) {
                                            var submenu = menu.addItem('Nuevo', null, null);
                                            menu.addItem('Página', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                var ve_modal = $('#ve_new_page');
                                                ve_modal.find('form')[0].reset();
                                                ve_modal.modal('show');
                                                ve_modal.on('hide.bs.modal', function() {
                                                });
                                                ve_modal.find('.btn-success').on('click', function() {
                                                    $(this).unbind('click');
                                                    var title = ve_modal.find('[name^="title["]').val();
                                                    graph.insertVertex(parent, null, title, mxPoint.x, mxPoint.y, 120, 160, 'shape=page;whiteSpace=wrap;');
                                                    ve_modal.modal('hide');
                                                });

                                            }, submenu);
                                            menu.addItem('Página Múltiple', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nueva página múltiple', mxPoint.x, mxPoint.y, 120, 160, 'shape=page-multi;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addSeparator(submenu);
                                            menu.addItem('Documento', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo documento', mxPoint.x, mxPoint.y, 120, 160, 'shape=document;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addItem('Documento Múltiple', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo documento múltiple', mxPoint.x, mxPoint.y, 120, 160, 'shape=document-multi;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addSeparator(submenu);
                                            menu.addItem('Grupo de páginas', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo grupo de páginas', mxPoint.x, mxPoint.y, 40, 40, 'shape=cluster;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addItem('Selector condicional', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo selector condicional', mxPoint.x, mxPoint.y, 120, 40, 'shape=conditional-selector;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addItem('Decisión', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nueva decisión', mxPoint.x, mxPoint.y, 40, 40, 'shape=decision;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addItem('Elección múltiple', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nueva elección múltiple', mxPoint.x, mxPoint.y, 40, 40, 'shape=multiple-choice;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addSeparator(submenu);
                                            menu.addItem('Área común', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo área común', mxPoint.x, mxPoint.y, 400, 400, 'shape=common-area;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addItem('Área común múltiple', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo área común múltiple', mxPoint.x, mxPoint.y, 400, 400, 'shape=common-area-multi;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addItem('Área condicional', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo área condicional', mxPoint.x, mxPoint.y, 400, 400, 'shape=conditional-area;whiteSpace=wrap;');
                                            }, submenu);
                                            menu.addItem('Área condicional múltiple', null, function()
                                            {
                                                var mxPoin = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo área condicional múltiple', mxPoint.x, mxPoint.y, 400, 400, 'shape=conditional-area-multi;whiteSpace=wrap;');
                                            }, submenu);
                                        } else {
                                            var submenu = menu.addItem('Orden', null, null);
                                            menu.addItem('Enviar al fondo', null, function()
                                            {
                                                var parent = cell.getParent();
                                                if (parent !== null) {
                                                    model.beginUpdate();
                                                    cell.removeFromParent();
                                                    parent.insert(cell, 0);
                                                    model.endUpdate();
                                                    graph.graphModelChanged([parent, cell]);
                                                }
                                            }, submenu);
                                            menu.addItem('Traer al frente', null, function()
                                            {
                                                var parent = cell.getParent();
                                                if (parent !== null) {
                                                    model.beginUpdate();
                                                    cell.removeFromParent();
                                                    parent.insert(cell);
                                                    model.endUpdate();
                                                    graph.graphModelChanged([parent, cell]);
                                                }
                                            }, submenu);
                                        }
                                    };

                                    $('#modal_visual_editor_targets .btn-zoom-in').on('click', function() {
                                        graph.zoomIn();
                                    });

                                    $('#modal_visual_editor_targets .btn-zoom-out').on('click', function() {
                                        graph.zoomOut();
                                    });

                                    $('#modal_visual_editor_targets .btn-zoom-actual').on('click', function() {
                                        graph.zoomActual();
                                    });

                                    $('#modal_visual_editor_targets .btn-zoom-fit').on('click', function() {
                                        //graph.zoomToRect(graph.getCellBounds(graph.getDefaultParent(), true, true));
                                        var margin = 20;
                                        var max = 3;

                                        var bounds = graph.getGraphBounds();
                                        var cw = graph.container.clientWidth - margin;
                                        var ch = graph.container.clientHeight - margin;
                                        var w = bounds.width / graph.view.scale;
                                        var h = bounds.height / graph.view.scale;
                                        var s = Math.min(max, Math.min(cw / w, ch / h));

                                        graph.view.scaleAndTranslate(s,
                                            (margin + cw - w * s) / (2 * s) - bounds.x / graph.view.scale,
                                            (margin + ch - h * s) / (2 * s) - bounds.y / graph.view.scale
                                        );
                                    });

                                    editor.readGraphModel(e.params.xml.documentElement);

                                    mxHierarchicalLayout.prototype.disableEdgeStyle = false;
                                    var layout = new mxHierarchicalLayout(graph, mxConstants.DIRECTION_NORTH);
                                    /*var first = new mxFastOrganicLayout(graph);
                                    var second = new mxParallelEdgeLayout(graph);
                                    var layout = new mxCompositeLayout(graph, [first, second], first);* /
                                    var executeLayout = function(change, post)
                    				{
                    					graph.getModel().beginUpdate();
                    					try {
                    						if (change != null) {
                    							change();
                    						}
                    		    			layout.execute(graph.getDefaultParent(), v1);
                    					}
                    					catch (e) {
                    						throw e;
                    					} finally {
                    						// New API for animating graph layout results asynchronously
                    						var morph = new mxMorphing(graph);
                    						morph.addListener(mxEvent.DONE, mxUtils.bind(this, function()
                    						{
                    							graph.getModel().endUpdate();

                    							if (post != null) {
                    								post();
                    							}
                    						}));
                    						morph.startAnimation();
                    					}
                    				};
                                }
                            }));
                            ajax.execute();
                        });
                */
            }
        })
        .on('hide.bs.modal', function(e) {
            var vi_editor = $(e.target).find('[data-toggle="visual-editor"]');
            if (vi_editor.length > 0) {
                console.log(e);
            }
        })
    ;
});

function saveCellsGeometry(geom_cells) {
    nabu.loadLibrary('Ajax', function() {
        var cells = [];
        for (var i in geom_cells) {
            var cell = geom_cells[i];
            cells.push({
                "id": cell.id,
                "x": cell.geometry.x,
                "y": cell.geometry.y,
                "width": cell.geometry.width,
                "height": cell.geometry.height
            });
        }
        var ajax = new Nabu.Ajax.Connector(
            'http://cms.nabu-3.com/api/site/2/visual-editor/cell/?action=mass-geometry',
            'POST',
            {
                "headerAccept": "application/json",
                "contentType": "application/json"
            }
        );
        ajax.addEventListener(new Nabu.Event({
            onLoad: function(evt) {
                return true;
            }
        }));
        ajax.setPostJSON(cells);
        ajax.execute();
    });
}

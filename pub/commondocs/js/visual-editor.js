$(document).ready(function() {
    $('.modal')
        .on('shown.bs.modal', function(e) {
            var vi_editor = $(e.target).find('[data-toggle="visual-editor"]');
            if (vi_editor.length > 0) {
                if (!mxClient.isBrowserSupported()) {
                    // Displays an error message if the browser is not supported.
                    mxUtils.error('Browser is not supported!', 200, false);
                } else {
                    var config = mxUtils.load('/js/visual-editor/config/site-target.xml').getDocumentElement();
                    var editor = new mxEditor(config);
                    editor.setGraphContainer(vi_editor[0]);

                    var data = vi_editor.data();

                    if (data.source) {
                        nabu.loadLibrary('Ajax', function() {
                            var ajax = new Nabu.Ajax.Connector(data.source, 'GET');
                            ajax.addEventListener(new Nabu.Event({
                                onLoad: function(e) {
                                    mxGraphHandler.prototype.guidesEnabled = true;
                                    mxEvent.disableContextMenu(document.body);
                                    var graph = editor.graph;
                                    var model = graph.getModel();
                                    graph.setPanning(true);
                                    graph.setHtmlLabels(true);
                    				graph.panningHandler.useLeftButtonForPanning = false;
                    				//graph.setAllowDanglingEdges(false);
                                    graph.setConnectable(true);
                    				//graph.connectionHandler.select = false;
                    				graph.view.setTranslate(20, 20);
                                    graph.graphHandler.scaleGrid = true;
                                    //graph.setGridEnabled(true);
                                    //graph.setGridSize(20);
                                    var style = graph.getStylesheet().getDefaultVertexStyle();
                                    style[mxConstants.STYLE_FILLCOLOR] = '#ffffff';

                                    style = graph.getStylesheet().getDefaultEdgeStyle();
                    				style[mxConstants.STYLE_ROUNDED] = false;
                    				style[mxConstants.STYLE_EDGE] = mxEdgeStyle.ElbowConnector;

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
                    				graph.alternateEdgeStyle = 'elbow=vertical';

                                    // Changes the default edge style
                    				//graph.getStylesheet().getDefaultEdgeStyle()['edgeStyle'] = 'orthogonalEdgeStyle';
                    				//delete graph.getStylesheet().getDefaultEdgeStyle()['endArrow'];

                    				// Implements the connect preview
                                    /*
                    				graph.connectionHandler.createEdgeState = function(me)
                    				{
                    					var edge = graph.createEdge(null, null, null, null, null);

                    					return new mxCellState(this.graph.view, edge, this.graph.getCellStyle(edge));
                    				};
                                    */
                                    //mxConnectionHandler.prototype.createTarget = true;
                                    graph.connectionHandler.addListener(mxEvent.START, function(sender, evt) {
                                        console.log('Start');
                                        console.log(sender);
                                        console.log(evt);
                                    });

                                    graph.connectionHandler.addListener(mxEvent.CONNECT, function(sender, evt) {
                                        console.log('Connect');
                                        console.log(sender);
                                        console.log(evt);
                                        nabu.loadLibrary('Ajax', function() {
                                            var ajax = new Nabu.Ajax.Connector(
                                                'http://cms.nabu-3.com/api/site/2/visual-editor/cell/st-1029',
                                                'POST',
                                                {
                                                    "headerAccept": "application/json",
                                                    "contentType": "application/json"
                                                }
                                            );
                                            ajax.setPostJSON({
                                                "x": 100,
                                                "y": 200
                                            });
                                            ajax.execute();
                                        });
                                    });

                                    mxVertexHandler.prototype.livePreview = true;

                                    // Automatically handle parallel edges
                                    var layout = new mxParallelEdgeLayout(graph);
                                    var layoutMgr = new mxLayoutManager(graph);

                                    layoutMgr.getLayout = function(cell)
                                    {
                                        if (cell.getChildCount() > 0)
                                        {
                                            return layout;
                                        }
                                    };

                                    new mxRubberband(graph);

                                    // Create grid dynamically (requires canvas)
                                    (function()
                                    {
                                        try
                                        {
                                            var canvas = document.createElement('canvas');
                                            canvas.style.position = 'absolute';
                                            canvas.style.top = '0px';
                                            canvas.style.left = '0px';
                                            canvas.style.zIndex = -1;
                                            graph.container.appendChild(canvas);

                                            var ctx = canvas.getContext('2d');

                                            // Modify event filtering to accept canvas as container
                                            var mxGraphViewIsContainerEvent = mxGraphView.prototype.isContainerEvent;
                                            mxGraphView.prototype.isContainerEvent = function(evt)
                                            {
                                                return mxGraphViewIsContainerEvent.apply(this, arguments) ||
                                                    mxEvent.getSource(evt) == canvas;
                                            };

                                            var s = 0;
                                            var gs = 0;
                                            var tr = new mxPoint();
                                            var w = 0;
                                            var h = 0;

                                            function repaintGrid()
                                            {
                                                if (ctx != null)
                                                {
                                                    var bounds = graph.getGraphBounds();
                                                    var width = Math.max(bounds.x + bounds.width, graph.container.clientWidth);
                                                    var height = Math.max(bounds.y + bounds.height, graph.container.clientHeight);
                                                    var sizeChanged = width != w || height != h;

                                                    if (graph.view.scale != s || graph.view.translate.x != tr.x || graph.view.translate.y != tr.y ||
                                                        gs != graph.gridSize || sizeChanged)
                                                    {
                                                        tr = graph.view.translate.clone();
                                                        s = graph.view.scale;
                                                        gs = graph.gridSize;
                                                        w = width;
                                                        h = height;

                                                        // Clears the background if required
                                                        if (!sizeChanged)
                                                        {
                                                            ctx.clearRect(0, 0, w, h);
                                                        }
                                                        else
                                                        {
                                                            canvas.setAttribute('width', w);
                                                            canvas.setAttribute('height', h);
                                                        }

                                                        var tx = tr.x * s;
                                                        var ty = tr.y * s;

                                                        // Sets the distance of the grid lines in pixels
                                                        var minStepping = graph.gridSize;
                                                        var stepping = minStepping * s;

                                                        if (stepping < minStepping)
                                                        {
                                                            var count = Math.round(Math.ceil(minStepping / stepping) / 2) * 2;
                                                            stepping = count * stepping;
                                                        }

                                                        var xs = Math.floor((0 - tx) / stepping) * stepping + tx;
                                                        var xe = Math.ceil(w / stepping) * stepping;
                                                        var ys = Math.floor((0 - ty) / stepping) * stepping + ty;
                                                        var ye = Math.ceil(h / stepping) * stepping;

                                                        xe += Math.ceil(stepping);
                                                        ye += Math.ceil(stepping);

                                                        var ixs = Math.round(xs);
                                                        var ixe = Math.round(xe);
                                                        var iys = Math.round(ys);
                                                        var iye = Math.round(ye);

                                                        // Draws the actual grid

                                                        for (var x = xs, c = 0; x <= xe; x += stepping, c = (c + 1) % 10)
                                                        {
                                                            x = Math.round((x - tx) / stepping) * stepping + tx;
                                                            var ix = Math.round(x);

                                                            if (c === 0) {
                                                                ctx.strokeStyle = '#93a1a140';
                                                            } else {
                                                                ctx.strokeStyle = '#eee8d5';
                                                            }
                                                            ctx.beginPath();
                                                            ctx.moveTo(ix + 0.5, iys + 0.5);
                                                            ctx.lineTo(ix + 0.5, iye + 0.5);
                                                            ctx.closePath();
                                                            ctx.stroke();
                                                        }

                                                        for (var y = ys, c = 0; y <= ye; y += stepping, c = (c + 1) % 10)
                                                        {
                                                            y = Math.round((y - ty) / stepping) * stepping + ty;
                                                            var iy = Math.round(y);

                                                            if (c === 0) {
                                                                ctx.strokeStyle = '#93a1a140';
                                                            } else {
                                                                ctx.strokeStyle = '#eee8d5';
                                                            }
                                                            ctx.beginPath();
                                                            ctx.moveTo(ixs + 0.5, iy + 0.5);
                                                            ctx.lineTo(ixe + 0.5, iy + 0.5);
                                                            ctx.closePath();
                                                            ctx.stroke();
                                                        }

                                                    }
                                                }
                                            };
                                        }
                                        catch (e)
                                        {
                                            mxLog.show();
                                            mxLog.debug('Using background image');

                                            container.style.backgroundImage = 'url(\'editors/images/grid.gif\')';
                                        }

                                        var mxGraphViewValidateBackground = mxGraphView.prototype.validateBackground;
                                        mxGraphView.prototype.validateBackground = function()
                                        {
                                            mxGraphViewValidateBackground.apply(this, arguments);
                                            repaintGrid();
                                        };
                                    })();

                                    // Configures automatic expand on mouseover
                                    graph.popupMenuHandler.autoExpand = true;

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
                                    var layout = new mxCompositeLayout(graph, [first, second], first);*/
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

                                    /*
                                    var edgeHandleConnect = mxEdgeHandler.prototype.connect;
                    				mxEdgeHandler.prototype.connect = function(edge, terminal, isSource, isClone, me)
                    				{
                    					edgeHandleConnect.apply(this, arguments);
                    					executeLayout();
                    				};
                                    */

                                    /*
                    				graph.resizeCell = function()
                    				{
                    					mxGraph.prototype.resizeCell.apply(this, arguments);

                    					executeLayout();
                    				};
                                    */

                                    /*
                    				graph.connectionHandler.addListener(mxEvent.CONNECT, function()
                    				{
                    					executeLayout();
                    				});
                                    */
                                    //executeLayout();

                                    var layout = new mxParallelEdgeLayout(graph);

                                    graph.addListener(mxEvent.CELL_CONNECTED, function(sender, evt)
                                    {
                                        var model = graph.getModel();
                                        var edge = evt.getProperty('edge');
                                        var src = model.getTerminal(edge, true);
                                        var trg = model.getTerminal(edge, false);

                                        layout.isEdgeIgnored = function(edge2)
                                        {
                                            var src2 = model.getTerminal(edge2, true);
                                            var trg2 = model.getTerminal(edge2, false);

                                            return !(model.isEdge(edge2) && ((src == src2 && trg == trg2) || (src == trg2 && trg == src2)));
                                        };

                                        layout.execute(graph.getDefaultParent());
                                    });

                                }
                            }));
                            ajax.execute();
                        });
                    } else {
                        var graph = editor.graph;
                        var model = graph.getModel();

                        // Gets the default parent for inserting new cells. This
                        // is normally the first child of the root (ie. layer 0).
                        var parent = graph.getDefaultParent();

                        // Adds cells to the model in a single step
                        model.beginUpdate();
                        try
                        {
                          var v1 = graph.insertVertex(parent, null, 'Hello,', 20, 20, 80, 30);
                          var v2 = graph.insertVertex(parent, null, 'World!', 200, 150, 80, 30);
                          var e1 = graph.insertEdge(parent, null, '', v1, v2);
                        }
                        finally
                        {
                          // Updates the display
                          model.endUpdate();
                        }
                    }
                }
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

function PageShape()
{
    mxCylinder.call(this);
}
mxUtils.extend(PageShape, mxCylinder);
PageShape.prototype.redrawPath = function(path, x, y, w, h, isForeground)
{
    if (isForeground) {
    } else {
        path.moveTo(0, 0);
        path.lineTo(w, 0);
        path.lineTo(w, h);
        path.lineTo(0, h);
        path.close();
    }
}
mxCellRenderer.prototype.defaultShapes['page'] = PageShape;

function DocumentShape()
{
    mxCylinder.call(this);
}
mxUtils.extend(DocumentShape, mxCylinder);
DocumentShape.prototype.redrawPath = function(path, x, y, w, h, isForeground)
{
    var fold = (w > 0 ? Math.round(Math.min(w * 0.10, h * 0.10)) : 0);

    if (isForeground) {
        if (fold > 0) {
            path.moveTo(w - fold, 0);
            path.lineTo(w - fold, fold);
            path.lineTo(w, fold);
        }
    } else {
        path.moveTo(0, 0);
        path.lineTo(w - fold, 0);
        path.lineTo(w, fold);
        path.lineTo(w, h);
        path.lineTo(0, h);
        path.lineTo(0, 0);
        path.close();
    }
}
mxCellRenderer.prototype.defaultShapes['document'] = DocumentShape;

function PageMultiShape()
{
    mxCylinder.call(this);
}
mxUtils.extend(PageMultiShape, mxCylinder);
PageMultiShape.prototype.redrawPath = function(path, x, y, w, h, isForeground)
{
    var fold = (w > 0 ? Math.round(Math.min(w * 0.10, h * 0.10)) : 0);
    var rep = Math.round(fold * 0.5);
    var rep2 = rep * 2;

    if (isForeground) {
        if (rep > 0) {
            path.moveTo(0, h - rep2);
            path.lineTo(w - rep2, h - rep2);
            path.lineTo(w - rep2, 0);
            path.moveTo(rep, h - rep);
            path.lineTo(w - rep, h - rep);
            path.lineTo(w - rep, rep);
        }
    } else {
        path.moveTo(0, 0);
        path.lineTo(w - rep2, 0);
        path.lineTo(w - rep2, rep);
        path.lineTo(w - rep, rep);
        path.lineTo(w - rep, rep2);
        path.lineTo(w, rep2);
        path.lineTo(w, h);
        path.lineTo(rep2, h);
        path.lineTo(rep2, h - rep);
        path.lineTo(rep, h - rep);
        path.lineTo(rep, h - rep2);
        path.lineTo(0, h - rep2);
        path.close();
    }
}
mxCellRenderer.prototype.defaultShapes['page-multi'] = PageMultiShape;

function DocumentMultiShape()
{
    mxCylinder.call(this);
}
mxUtils.extend(DocumentMultiShape, mxCylinder);
DocumentMultiShape.prototype.redrawPath = function(path, x, y, w, h, isForeground)
{
    var fold = (w > 0 ? Math.round(Math.min(w * 0.10, h * 0.10)) : 0);
    var rep = Math.round(fold * 0.5);
    var rep2 = rep * 2;

    if (isForeground) {
        if (fold > 0) {
            path.moveTo(w - fold - rep2, 0);
            path.lineTo(w - fold - rep2, fold);
            path.lineTo(w - rep2, fold);
            path.lineTo(w - rep2, h - rep2);
            path.lineTo(0, h - rep2);
            path.moveTo(rep2, h - rep);
            path.lineTo(w - rep, h - rep);
            path.lineTo(w - rep, fold + rep);
            path.lineTo(w - rep2, fold + rep);
            path.moveTo(w - rep, fold + rep2);
            path.lineTo(w, fold + rep2);
        }
    } else {
        path.moveTo(0, 0);
        path.lineTo(w - fold - rep2, 0);
        path.lineTo(w, fold + rep2, 0);
        path.lineTo(w, h);
        path.lineTo(rep2, h);
        path.lineTo(rep2, h - rep);
        path.lineTo(rep, h - rep);
        path.lineTo(rep, h - rep2);
        path.lineTo(0, h - rep2);
        path.close();
    }
}
mxCellRenderer.prototype.defaultShapes['document-multi'] = DocumentMultiShape;

function ConditionalSelectorShape()
{
    mxCylinder.call(this);
}
mxUtils.extend(ConditionalSelectorShape, mxCylinder);
ConditionalSelectorShape.prototype.redrawPath = function(path, x, y, w, h, isForeground)
{
    var fold = (w > 0 ? Math.round(w / 4) : 0);

    if (isForeground) {

    } else {
        path.moveTo(fold, 0);
        path.lineTo(w - fold, 0);
        path.lineTo(w, h);
        path.lineTo(0, h);
        path.close();
    }
}
mxCellRenderer.prototype.defaultShapes['conditional-selector'] = ConditionalSelectorShape;

function ClusterShape()
{
    mxEllipse.call(this);
}
mxUtils.extend(ClusterShape, mxEllipse);
mxCellRenderer.prototype.defaultShapes['cluster'] = ClusterShape;

function DecisionShape()
{
    mxRhombus.call(this);
}
mxUtils.extend(DecisionShape, mxRhombus);
mxCellRenderer.prototype.defaultShapes['decision'] = DecisionShape;

function MultipleChoiceShape()
{
    mxTriangle.call(this);
}
mxUtils.extend(MultipleChoiceShape, mxTriangle);
MultipleChoiceShape.prototype.redrawPath = function(path, x, y, w, h, isForeground)
{
    if (isForeground) {

    } else {
        path.moveTo(w / 2, 0);
        path.lineTo(w, h);
        path.lineTo(0, h);
        path.close();
    }
}
MultipleChoiceShape.prototype.apply = function(state)
{
    this.isDashed = true;
    mxTriangle.prototype.apply.apply(this, arguments);
    this.state = state;
}
mxCellRenderer.prototype.defaultShapes['multiple-choice'] = MultipleChoiceShape;

function CommonAreaShape()
{
    mxCylinder.call(this);
}
mxUtils.extend(CommonAreaShape, mxCylinder);
CommonAreaShape.prototype.redrawPath = function(path, x, y, w, h, isForeground)
{
    if (isForeground) {

    } else {
        path.roundrect(0, 0, w, h, this.style[mxConstants.STYLE_ARCSIZE], this.style[mxConstants.STYLE_ARCSIZE]);
    }
}
CommonAreaShape.prototype.apply = function(state)
{
    mxCylinder.prototype.apply.apply(this, arguments);
    this.isDashed = false;
    this.isRounded = true;
    this.state = state;
    this.style[mxConstants.STYLE_ARCSIZE] = 10;
}
mxCellRenderer.prototype.defaultShapes['common-area'] = CommonAreaShape;

function CommonAreaMultiShape()
{
    CommonAreaShape.call(this);
}
mxUtils.extend(CommonAreaMultiShape, CommonAreaShape);
CommonAreaMultiShape.prototype.redrawPath = function(path, x, y, w, h, isForeground)
{
    var fold = (w > 0 ? Math.round(Math.min(w * 0.10, h * 0.10)) : 0);
    var rep = Math.round(fold * 0.5);
    var rep2 = rep * 2;
    var arc = this.style[mxConstants.STYLE_ARCSIZE];
    var arc2 = arc * 2;
    var arc3 = arc * 3;

    if (isForeground) {
        path.moveTo(w - arc2, arc);
        path.lineTo(w - arc2, h - arc3);
        path.arcTo(arc, arc, 0, 0, 1, w - arc3, h - arc2);
        path.lineTo(arc, h - arc2);
        path.moveTo(w - arc, arc2);
        path.lineTo(w - arc, h - arc2);
        path.arcTo(arc, arc, 0, 0, 1, w - arc2, h - arc);
        path.lineTo(arc2, h - arc);
    } else {
        path.moveTo(0, arc);
        path.arcTo(arc, arc, 0, 0, 1, arc, 0);
        path.lineTo(w - arc3, 0);
        path.arcTo(arc, arc, 0, 0, 1, w - arc2, arc);
        path.arcTo(arc, arc, 0, 0, 1, w - arc, arc2);
        path.arcTo(arc, arc, 0, 0, 1, w, arc3);
        path.lineTo(w, h - arc);
        path.arcTo(arc, arc, 0, 0, 1, w - arc, h);
        path.lineTo(arc3, h);
        path.arcTo(arc, arc, 0, 0, 1, arc2, h - arc);
        path.arcTo(arc, arc, 0, 0, 1, arc, h - arc2);
        path.arcTo(arc, arc, 0, 0, 1, 0, h - arc3);
        path.close();
    }
}
mxCellRenderer.prototype.defaultShapes['common-area-multi'] = CommonAreaMultiShape;

function ConditionalAreaShape()
{
    CommonAreaShape.call(this);
}
mxUtils.extend(ConditionalAreaShape, CommonAreaShape);
ConditionalAreaShape.prototype.apply = function(state)
{
    CommonAreaShape.prototype.apply.apply(this, arguments);
    this.isDashed = true;
    this.isRounded = true;
    this.state = state;
    this.style[mxConstants.STYLE_ARCSIZE] = 10;
}
mxCellRenderer.prototype.defaultShapes['conditional-area'] = ConditionalAreaShape;

function ConditionalAreaMultiShape()
{
    CommonAreaMultiShape.call(this);
}
mxUtils.extend(ConditionalAreaMultiShape, CommonAreaMultiShape);
ConditionalAreaMultiShape.prototype.apply = function(state)
{
    CommonAreaMultiShape.prototype.apply.apply(this, arguments);
    this.isDashed = true;
    this.isRounded = true;
    this.state = state;
    this.style[mxConstants.STYLE_ARCSIZE] = 10;
}
mxCellRenderer.prototype.defaultShapes['conditional-area-multi'] = ConditionalAreaMultiShape;

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
                    				graph.panningHandler.useLeftButtonForPanning = false;
                    				graph.setAllowDanglingEdges(false);
                                    graph.setConnectable(true);
                    				//graph.connectionHandler.select = false;
                    				graph.view.setTranslate(20, 20);
                                    graph.graphHandler.scaleGrid = true;
                                    //graph.setGridEnabled(true);
                                    //graph.setGridSize(20);
                                    style = graph.getStylesheet().getDefaultVertexStyle();
                                    style[mxConstants.STYLE_FILLCOLOR] = '#ffffff';

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
                                                graph.insertVertex(parent, null, 'Nueva página', mxPoint.x, mxPoint.y, 120, 160, 'shape=page;');
                                            }, submenu);
                                            menu.addItem('Página Múltiple', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nueva página múltiple', mxPoint.x, mxPoint.y, 120, 160, 'shape=page-multi');
                                            }, submenu);
                                            menu.addSeparator(submenu);
                                            menu.addItem('Documento', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo documento', mxPoint.x, mxPoint.y, 120, 160, 'shape=document;');
                                            }, submenu);
                                            menu.addItem('Documento Múltiple', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo documento múltiple', mxPoint.x, mxPoint.y, 120, 160, 'shape=document-multi;');
                                            }, submenu);
                                            menu.addSeparator(submenu);
                                            menu.addItem('Grupo de páginas', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo grupo de páginas', mxPoint.x, mxPoint.y, 40, 40, 'shape=cluster');
                                            }, submenu);
                                            menu.addItem('Selector condicional', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo selector condicional', mxPoint.x, mxPoint.y, 120, 40, 'shape=conditional-selector');
                                            }, submenu);
                                            menu.addItem('Decisión', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nueva decisión', mxPoint.x, mxPoint.y, 40, 40, 'shape=decision');
                                            }, submenu);
                                            menu.addItem('Elección múltiple', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nueva elección múltiple', mxPoint.x, mxPoint.y, 40, 40, 'shape=multiple-choice');
                                            }, submenu);
                                            menu.addSeparator(submenu);
                                            menu.addItem('Área común', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo área común', mxPoint.x, mxPoint.y, 280, 100, 'shape=common-area');
                                            }, submenu);
                                            menu.addItem('Área condicional', null, function()
                                            {
                                                var mxPoint = graph.getPointForEvent(evt);
                                                graph.insertVertex(parent, null, 'Nuevo área condicional', mxPoint.x, mxPoint.y, 280, 100, 'shape=conditional-area');
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
                                        graph.zoomToRect(graph.getCellBounds(graph.getDefaultParent(), true, true));
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
                                    executeLayout();

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
            path.moveTo(0, fold);
            path.lineTo(fold, fold);
            path.lineTo(fold, 0);
        }
    } else {
        path.moveTo(fold, 0);
        path.lineTo(w, 0);
        path.lineTo(w, h);
        path.lineTo(0, h);
        path.lineTo(0, fold);
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
            path.moveTo(0, fold);
            path.lineTo(fold, fold);
            path.lineTo(fold, 0);
        }
        if (rep > 0) {
            path.moveTo(0, h - rep2);
            path.lineTo(w - rep2, h - rep2);
            path.lineTo(w - rep2, 0);
            path.moveTo(rep, h - rep);
            path.lineTo(w - rep, h - rep);
            path.lineTo(w - rep, rep);
        }
    } else {
        path.moveTo(fold, 0);
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
        path.lineTo(0, fold);
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
    mxRectangleShape.call(this);
}
mxUtils.extend(CommonAreaShape, mxRectangleShape);
CommonAreaShape.prototype.apply = function(state)
{
    this.isDashed = false;
    this.isRounded = true;
    mxRectangleShape.prototype.apply.apply(this, arguments);
    this.state = state;
}
mxCellRenderer.prototype.defaultShapes['common-area'] = CommonAreaShape;

function ConditionalAreaShape()
{
    mxRectangleShape.call(this);
}
mxUtils.extend(ConditionalAreaShape, mxRectangleShape);
ConditionalAreaShape.prototype.apply = function(state)
{
    this.isDashed = true;
    this.isRounded = true;
    mxRectangleShape.prototype.apply.apply(this, arguments);
    this.state = state;
}
mxCellRenderer.prototype.defaultShapes['conditional-area'] = ConditionalAreaShape;

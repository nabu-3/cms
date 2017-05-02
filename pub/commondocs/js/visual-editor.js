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
                                    editor.readGraphModel(e.params.xml.documentElement);
                                    var graph = editor.graph;
                                    var model = graph.getModel();
                                    graph.setPanning(true);
                    				graph.panningHandler.useLeftButtonForPanning = false;
                    				graph.setAllowDanglingEdges(false);
                    				graph.connectionHandler.select = false;
                    				graph.view.setTranslate(20, 20);
                                    graph.setGridEnabled(true);
                                    graph.setGridSize(20);
                                    new mxRubberband(graph);
                                    var layout = new mxHierarchicalLayout(graph, mxConstants.DIRECTION_WEST);
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
                                    var edgeHandleConnect = mxEdgeHandler.prototype.connect;
                    				mxEdgeHandler.prototype.connect = function(edge, terminal, isSource, isClone, me)
                    				{
                    					edgeHandleConnect.apply(this, arguments);
                    					executeLayout();
                    				};

                    				graph.resizeCell = function()
                    				{
                    					mxGraph.prototype.resizeCell.apply(this, arguments);

                    					executeLayout();
                    				};

                    				graph.connectionHandler.addListener(mxEvent.CONNECT, function()
                    				{
                    					executeLayout();
                    				});
                                    executeLayout();
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

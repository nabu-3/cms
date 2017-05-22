$(document).ready(function() {
    $('.modal')
        .on('shown.bs.modal', function(e) {
            var vi_editor = $(e.target).find('[data-toggle="ve-site"]');
            if (vi_editor.length > 0) {
                vi_editor.nabuVESiteEditor('show');
                /*
                    if (data.source) {
                        nabu.loadLibrary('Ajax', function() {
                            var ajax = new Nabu.Ajax.Connector(data.source, 'GET');
                            ajax.addEventListener(new Nabu.Event({
                                onLoad: function(e) {
                                    var graph = editor.graph;
                                    var model = graph.getModel();
                    				graph.view.setTranslate(20, 20);

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

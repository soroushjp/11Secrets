function permalinkExpand() {

					var id = "link-box";

					var height = document.getElementById(id).offsetHeight;

					var newHeight = "45px";

					if(height != 0) {
						document.getElementById(id).style.height = '0px';
					}
					else {
						document.getElementById(id).style.height = newHeight;
					}

				}
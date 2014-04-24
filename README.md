Retina Images für Contao
========================

Diese Erweiterung erzeugt automatisch zu jedem skalierten Bild ein zweites @2x-Bild mit der doppelten Auflösung, sofern das Ausgangsbild die notwendige Größe hat. Dazu werden im Cache-Verzeichnis `system/html` jeweils ein normal skaliertes und ein gleichnamiges doppelt so großes Bild erstellt, welches vor der Endung den Zusatz @2x hat.

Über ein kleines Javascript werden dann bei den passenden Geräten wie dem iPad 3, iPhone 4 oder MacBook Pro Retina die Bilder mit der hochauflösenden Variante – sofern vorhanden – ausgetauscht.

Nach der Installation der Erweiterung muss über die Systemwartung der Cache in system/html gelöscht werden, damit die Bilder neu erzeugt werden.

Liegen für andere Bilder wie bspw. Layout-Grafiken die hochauflösenden Bilder mit dem Zusatz @2x im gleichen Verzeichnis wie das Ausgangsbild, werden auch diese Bilder automatisch eingebunden.

Scalable Vector Graphics – SVG
------------------------------

Die Erweiterung ermöglicht es auch, SVG-Bilder einzubinden. Dazu muss in den System-Einstellungen von Contao unter *Dateien und Bilder* bei *Unterstützte Bildformate* und unter *Datei-Upload* bei *Erlaubte Upload-Dateitypen* die Endung `svg` hinzugefügt werden. Danach können in jedem Standard-Inhaltselement wie *Bild* oder *Text* auch SVG-Bilder ausgewählt und eingebunden werden. Die Erweiterung sorgt dafür, dass die Größenangaben und das Seitenverhältnis richtig gesetzt werden. Wichtig ist, dass die XML-Dateien entwender eine `viewBox` oder Größenangaben `width` und `height` haben und das in PHP `simplexml` verfügbar ist.
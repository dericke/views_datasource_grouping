
Views Datasource README
---------------------------------------

Current Version
---------------
6.x-0.1-dev

Release Notes
-------------
CVS module created and intial code uploaded to repository. This is a 
proof-of-concept release with a working views_json plugin for Drupal 6. In the
Views interface simply select the view style as JSON data document (Exhibit 
format only) and the row style as Unformatted (separator must be |). Because the
template changes the page Content-type to text/javascript and immediately exits 
Drupal, the live preview will throw an error. This can be ignored; just save 
your view as a page and view it normally at the URL to get the JSON output.

About
-----
Views Datasource is a set of plugins for Views for rendering node content in a 
set of shareable, reusable data formats based on XML, JSON, and XHTML. These 
formats allow content in a Drupal site to be easily used as data sources for 
Semantic Web clients and web mash-ups. Views Datasource plugins output content 
from node lists created in Drupal Views as:
  1)XML data documents using schemas like OPML and Atom;
  2)RDF/XML and RDF/N3 data documents using a vocabulary like FOAF;
  3)JSON data documents in a format like MIT Simile/Exhibit JSON;
  4)XHTML data documents using a microformat like hCard
  
The project consists of 4 Views style plugins (and related row plugins):
  1)views_xml - Output as raw XML, OPML, and Atom;
  2)views_json - Output as Simile/Exhibit JSON, canonical JSON, JSONP;
  3)views_rdf - Output as FOAF, SIOC and DOAP;
  4)views_xhtml - Output as hCard, hCalendar, and Geo. 
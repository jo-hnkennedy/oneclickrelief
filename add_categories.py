import urllib2
import json

response = urllib2.urlopen("http://api.harveyneeds.org/api/v1/products")
data = json.loads(response.read())

for product in data["products"]:
	asin = product["asin"]
	resp_json = json.loads(urllib2.urlopen("http://localhost:8888/node_tree.php?ASIN=" + asin).read())
	print ",".join((asin, resp_json["asin"]))

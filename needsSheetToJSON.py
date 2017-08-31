import csv
import json
import re

#items = output JSON object
items = []

def is_number(s):
    try:
        float(s)
        return True
    except ValueError:
        return False

with open("needs.csv", "rb") as fh:
	reader = csv.reader(fh)
	for row in reader:
		if (is_number(row[2])):
			items.append({"name":row[0], "amazonID":row[1], "price":row[2]})

print json.dumps(items)

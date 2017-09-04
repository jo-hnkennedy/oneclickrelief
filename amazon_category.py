from bs4 import BeautifulSoup
import sys
import requests
import re

# add header
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36'}
asin = sys.argv[1]
url = "https://www.amazon.com/dp/" + asin + "/"
r = requests.get(url, headers=headers)
soup = BeautifulSoup(r.content, "lxml")

text = soup.find(id="SalesRank")

raw = re.sub("\n|\r", "", text.contents[2])
words = re.split("\s", raw)
output = words[3:]
output.pop()
print " ".join(output)

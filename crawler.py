from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.options import Options
from datetime import datetime, timedelta
from tqdm import tqdm

def ArrangeText(t):#t is maintext
    ret = ""
    tmp = t.split('\n')
    for i in tmp:
        ret += str(i) + " "        
    return ret
path = "C:\\Users\cmc9595\Desktop\crawl\chromedriver.exe"
url = "https://sports.news.naver.com/wfootball/news/index.nhn?isphoto=N&type=popular"

options = Options()
driver = webdriver.Chrome(path, options=options)
driver.implicitly_wait(5)
driver.get(url)

#1페이지 = 20
#하루 5페이지 = 100개
#3달 = 90일*100 = 9000개

#[date, pagenum, rownum, views, title, url, imgurl, maintext]
l = []
date = datetime.today()

for _ in tqdm(range(0, 90)): #90일치 출력하기
    print(date.strftime("%Y%m%d"))
    for page in range(1, 6): #1~5page
        url = "https://sports.news.naver.com/wfootball/news/index.nhn?page="+str(page)+"&isphoto=N&type=popular&date="+ date.strftime("%Y%m%d")
        driver.get(url)
        for num in range(1, 21):
            tmp = []
            tmp.append(date.strftime("%Y%m%d"))
            tmp.append(page)
            tmp.append(num)

            try:
                views = driver.find_element_by_xpath('//*[@id="_newsList"]/ul/li['+str(num)+']/div/div/span[2]')
                tmp.append(views.text)
            except:
                tmp.append("No조회수")

            try:
                title = driver.find_element_by_css_selector('#_newsList > ul > li:nth-child('+str(num)+') > div > a > span')
                tmp.append(title.text)
            except:
                tmp.append("NoTitle")
            
            try:
                article_url = driver.find_element_by_css_selector('#_newsList > ul > li:nth-child('+str(num)+') > div > a')
                tmp.append(article_url.get_attribute('href'))
            except:
                tmp.append("NoURL")

            try:
                img_url = driver.find_element_by_css_selector('#_newsList > ul > li:nth-child('+str(num)+') > a > img')
                tmp.append(img_url.get_attribute('src'))
            except:
                tmp.append("NoIMG")
            
            print(date.strftime("%Y%m%d"), page, num, title.text)

            try:
                driver.find_element_by_css_selector('#_newsList > ul > li:nth-child('+str(num)+') > div > a > span').click()
                b = driver.find_element_by_xpath('//*[@id="newsEndContents"]')
                tmp.append(ArrangeText(b.text))
            except:
                tmp.append("NoMainText")
            
            l.append(tmp)
            driver.back()
            
    date -= timedelta(1)

f = open("C:\\Users\cmc9595\Desktop\crawl\crawl.txt", "w", encoding="utf-8")
for a in l:
    s = ""
    s += str(a[0])+"|"+str(a[1])+"|"+str(a[2])+"|"+str(a[3])+"|"+a[4]+"|"+a[5]+"|"+a[6]+"|"+a[7]+"\n"
    f.write(s)
f.close()

'''
f = open("C:\\Users\myung\OneDrive\바탕 화면\crawl/crawl.txt", "r")
while True:
    line = f.readline()
    if not line: break
    print(line)
f.close()
'''

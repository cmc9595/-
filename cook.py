import pymysql
from konlpy.tag import Okt
from collections import Counter
from tqdm import tqdm

okt = Okt()
conn = pymysql.connect(
		user = 'root',
		passwd = 'root',
		host = 'localhost',
		db = 'articles',
		charset = 'utf8'
		)

curs = conn.cursor()

#sql = "SELECT * FROM articles WHERE title like '%손흥민%';"
sql = "SELECT * FROM articles;"
curs.execute(sql)

rows = curs.fetchall() #rows[0] ~
print(len(rows))

nouns = []
for i in tqdm(range(0, len(rows))): #title, maintext에있는 명사 추출
	nouns += okt.nouns(rows[i][4])
	nouns += okt.nouns(rows[i][7])

print(len(nouns))
count = Counter(nouns)

for i in count.most_common():
	sql = "INSERT INTO nouns(name, count) VALUES('"+i[0]+"', "+str(i[1])+");"
	curs.execute(sql)
	conn.commit()
conn.close()

## 从现有仓库克隆 git clone git项目地址
## 查看状态 git status
## 查看分支 git branch -a
## 所有文件都添加 git add .
## 提交 git commit -m "注释"
## 查看提交地址 git remote -v
## 推送到主干分支 git push origin master
## 创建新分支 git checkout -b dev
## 提交到分支 git push origin dev
## dev合并到主分支 
```$xslt
1、先切换到主分支
git checkout master
git merge dev 带上本地分支的名字
git add .
git commit -m ""
git push origin master
```
## 删除本地分支 git branch -D dev
## 删除远程分支 git push origin :dev
## 版本回退 git reset --hard head^
## 日志 git log






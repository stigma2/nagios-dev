# nagios-dev


# 나기오스 config 파일
    경로: config/nagios.php
    내용: 1.나기오스 로그인 정보, 2.나기오스 설정파일 쓰는 경로


# 컨트롤러
    경로: app/Http/Controllers/
    내용: HostController      - 호스트 관련 요청 처리
         ServiceController   - 서비스 관련 요청 처리
         CommandsController  - 커맨드 관련 오청 처리
         NagiosController    - 나기오스 상태, 재시작 요청 처리
         StatisticController - 나기오스 상태 개수, 로그

# 미들웨어 변경 사항
    경로: app/Http/Kernel.php
    내용: POST 요청시 VerifyCsrfToken 에러로 인해 주석처리
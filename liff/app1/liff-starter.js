/// event เมื่อโหลดหน้าเพจเรียบร้อยแล้ว ให้ใช้คำสั่ง liff.init() สำหรับเตรียมความพร้อมใช้คำสั่ง LIFF ต่างๆ
window.onload = function (e) {
    liff.init(function (data) { // คำสั่ง init() คืนค่าข้อมูลของ LIFF app 
        initializeApp(data); // เมื่อ liff พร้อมทำงาน เรียกฟังก์ชั่น initializeApp ส่งข้อมูล LIFF ไปแสดง
    });
};
 
// ฟังก์ชั่นสำหรับแสดงข้อมูล LIFF app และกำหนด การทำงานให้กับปุ่ม ต่างๆ 
function initializeApp(data) {
    document.getElementById('languagefield').textContent = data.language;
    document.getElementById('viewtypefield').textContent = data.context.viewType;
    document.getElementById('useridfield').textContent = data.context.userId;
    document.getElementById('utouidfield').textContent = data.context.utouId;
    document.getElementById('roomidfield').textContent = data.context.roomId;
    document.getElementById('groupidfield').textContent = data.context.groupId;
 
    // openWindow call
    document.getElementById('openwindowbutton').addEventListener('click', function () {
        liff.openWindow({
            url: 'https://line.me'
        });
    });
 
    // closeWindow call
    document.getElementById('closewindowbutton').addEventListener('click', function () {
        liff.closeWindow();
    });
 
    // sendMessages call
    document.getElementById('sendmessagebutton').addEventListener('click', function () {
        liff.sendMessages([{
            type: 'text',
            text: "You've successfully sent a message! Hooray!"
        }, {
            type: 'sticker',
            packageId: '2',
            stickerId: '144'
        }]).then(function () {
            window.alert("Message sent");
        }).catch(function (error) {
            window.alert("Error sending message: " + error);
        });
    });
 
    // get access token
    document.getElementById('getaccesstoken').addEventListener('click', function () {
        const accessToken = liff.getAccessToken();
        document.getElementById('accesstokenfield').textContent = accessToken;
        toggleAccessToken();
    });
 
    // get profile call
    document.getElementById('getprofilebutton').addEventListener('click', function () {
        liff.getProfile().then(function (profile) {
            document.getElementById('useridprofilefield').textContent = profile.userId;
            document.getElementById('displaynamefield').textContent = profile.displayName;
 
            const profilePictureDiv = document.getElementById('profilepicturediv');
            if (profilePictureDiv.firstElementChild) {
                profilePictureDiv.removeChild(profilePictureDiv.firstElementChild);
            }
            const img = document.createElement('img');
            img.src = profile.pictureUrl;
            img.alt = "Profile Picture";
            profilePictureDiv.appendChild(img);
 
            document.getElementById('statusmessagefield').textContent = profile.statusMessage;
            toggleProfileData();
        }).catch(function (error) {
            window.alert("Error getting profile: " + error);
        });
    });
}
 
//ฟังก์ชั่นสำหรับสลับซ่อนหรือแสดงค่า Access Token
function toggleAccessToken() {
    toggleElement('accesstokendata');
}
 
//ฟังก์ชั่นสำหรับสลับซ่อนหรือแสดงข้อมูลบัญชีผู้ใช้
function toggleProfileData() {
    toggleElement('profileinfo');
}
 
//ฟังกืชั่นสำหรับ สลับการซ่อนหรือแสดง html element ต่างๆ ตามค่าตัว element ที่ส่งมา
function toggleElement(elementId) {
    const elem = document.getElementById(elementId);
    if (elem.offsetWidth > 0 && elem.offsetHeight > 0) {
        elem.style.display = "none";
    } else {
        elem.style.display = "block";
    }
}
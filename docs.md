# <p align="center">Documentation of API</p>

<table>
    <tr>
        <th>Path
        <th>Method
        <th>Description
        <th>Request
        <th>Response
    </tr>
    <tr>
        <td><code>/api/lucky</code>
        <td><code>GET</code>
        <td>Returns array of lucky numbers for current day.
        <td>None
        <td><pre><code>{
    "numbers": [12, 18]    
}</code></pre>
    </tr>
    <tr>
        <td><code>/api/timetable/:class</code>
        <td><code>GET</code>
        <td>Returns object containing lessons info of given class.
        <td><code>:class</code> must be one of the following:
            <ul>
                <li><code>1lab</code>
                <li><code>1lah</code>
                <li><code>2la</code>
                <li><code>3la</code>
                <li><code>1ti</code>
                <li><code>2ti</code>
                <li><code>3ti</code>
                <li><code>4ti</code>
            </ul>
        <td><pre><code>{
    "timetables":
        1: {
            2: {
                "lesson": "Historia",
                "room": "4"
            },
            3: {
                "lesson": "Matematyka",
                "room": "6"
            },
            (...)
        },
        2: (...),
        (...)    
    }
}</code></pre>
        Hours without lessons must be left null.
    </tr>
    <tr>
        <td>/api/feed
        <td><code>GET</code>
        <td>Returns 5 most recent newses
        <td>None
        <td><pre><code>[
    {
        "author": "Samorząd",
        "publishedAt": 1509986869,
        "content": "Lorem ipsum...",
        "author": {
            "username": "j.kowalski"
        }
    },
    (...)
]</code></pre>
    </tr>
</table>

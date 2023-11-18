import React from 'react';

import {Badge, Box, Button, ChakraProvider, Container, Heading, Link, Table, Tbody, Td, Th, Tr} from '@chakra-ui/react'
import useSWR from 'swr'
import ErrorAlert from "../components/ErrorAlert";
import Loading from "../components/Loading";

export default function (props) {
    const fetcher = (...args) => fetch(...args).then(res => res.json())
    const params = {}
    if (props.id) {
        params.id = props.id
    }
    const query = new URLSearchParams(params)
    const {data, error, isLoading} = useSWR(`${props.path.render}?${query}`, fetcher)

    if (error) return <ErrorAlert/>
    if (isLoading) return <Loading/>

    const Submit = () => {
        let data = {
            ...{id: props.id},
            ...{token: props.token},
        }
        fetch(
            props.path.action, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then((response) => response.json())
            .then((responseJson) => {
                console.log("/task/" + responseJson.result.taskId)
                //window.location.href = "/task/" + responseJson.result.taskId;
            })
    }

    const runButton = data.item.showRunLink ? (
        <Button as='a' colorScheme='red' onClick={() => Submit()}>
            Run
        </Button>
    ) : null;

    const downloadButton = data.item.showDownloadLink ? (
        <Button as='a' colorScheme='blue' href={data.item.downloadLink}>
            Download
        </Button>
    ) : null;

    return (
        <ChakraProvider>
            <Container mt='2' mb='3'>
                <Heading size='lg'><Link href='/'>Phazz</Link></Heading>
            </Container>
            <Container>
                <Heading mb='3' size='md'>Task Information</Heading>
                <Table mb='2'>
                    <Tbody>
                        <Tr>
                            <Th>ID</Th>
                            <Td>{data.item.path}</Td>
                        </Tr>
                        <Tr>
                            <Th>Name</Th>
                            <Td>{data.item.name}</Td>
                        </Tr>
                        <Tr>
                            <Th>URL</Th>
                            <Td>{data.item.url}</Td>
                        </Tr>
                        <Tr>
                            <Th>Type</Th>
                            <Td>{data.item.type}</Td>
                        </Tr>
                        <Tr>
                            <Th>State</Th>
                            <Td>
                                <Badge colorScheme={data.item.stateColorScheme}>{data.item.state}</Badge>
                            </Td>
                        </Tr>
                    </Tbody>
                </Table>
                <Box mt='4' textAlign={"center"}>
                    {runButton}
                    {downloadButton}
                    <Button ml='2' as='a' href={data.item.jobLink}>
                        Go to Job
                    </Button>
                </Box>
            </Container>
        </ChakraProvider>
    )
}
